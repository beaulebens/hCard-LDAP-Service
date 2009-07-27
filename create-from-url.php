<?php
// NOTE: Not in remotely working condition. Requires an hCard parser of some sort!

// require 'hkit.class.php';
// require 'lib-xml-serialize.php';

if ( empty( $_GET['url'] ) )
	die( 'Missing required ?url=' );

$ldap = ldap_connect( 'ldap://twitfave.com/' );
if ( $ldap ) {
	$bind = ldap_bind( $ldap, 'cn=admin,dc=nodomain', 'mfslapd%%' );
	if ( !$bind ) {
		echo 'Failed to connect to LDAP server!';
		exit;
	}
	
	$xml = file_get_contents( 'http://microformatique.com/optimus/?format=json&function=&filter=hcard&uri=' . rawurlencode( $_GET['url'] ) );
	$xml = unserialize( xml_serialize( $xml ) );
	
	foreach ( $xml[0]['c'] as $hC ) {
		// Search for something that would identify this card (email, name?)
		// Update if found, or create a new one
		
		$hC = $hC['c'];
		
		$dn = 'uid=' . md5( microtime() ) . ',o=person,dc=nodomain';
	
		$entry = array();
		$entry['objectClass']     = array( 'top', 'person', 'organizationalPerson', 'inetOrgPerson', 'vCardPerson' );

		$entry['cn']              = array( $hC['fn'], /*$hC['nickname']*/ ); // Common Name
//		$entry['personalTitle']   = array( $hC['n']['honorific-prefix'] );
//		$entry['generationQualifier'] = array( $hC['n']['honorific-suffix'] ); // Jr, Sn, III
		$entry['gn']              = array( xml_get_element( $n['c'], 'given-name', false, true )  ); // Given Name
		$entry['sn']              = array( xml_get_element( $n['c'], 'family-name', false, true ) ); // Surname/Family Name
		$entry['displayName']     = array( xml_get_element( $hC, 'fn', false, true ) ); // Nickname

print_r(xml_get_element( $hC, 'email' ));
exit;

		$entry['mail']            = (array) $hC['email']; // Email
		$entry['labeledURI']      = (array) $hC['url']; // All URLS (homepage, YIM etc)

		// Switch on tel type for telephoneNumber, homePhone and mobile
		$entry['mobile']          = array( $hC['tel'] ); // Mobile number

		//$entry['postOfficeBox']   = array( $hC['adr']['post-office-box'] ); // PO Box
		$entry['postalAddress']   = array( implode( ', ', $hC['adr'] ) ); // Mailing address, preformatted (homePostalAddress)
		$entry['street']          = array( $hC['adr']['street-address'] ); 
		$entry['l']               = array( $hC['adr']['locality'] ); // City
		$entry['st']              = array( $hC['adr']['region'] ); // State
		$entry['postalCode']      = array( $hC['adr']['postal-code'] ); // ZIP
		$entry['co']              = array( $hC['adr']['country-name'] ); // Country
		
		$hC['org'] = (array) $hC['org'];
		$entry['o']               = array( is_array( $hC['org'][0] ) ? $hC['org'][0]['organization-name'] : $hC['org'][0] );
		$entry['ou']              = array( $hC['org'][0]['organization-unit'] );
		
		$entry['description']     = array( implode( ', ', $hC['adr'] ) );
		
		print_r($entry);
		exit;
		
		if ( !ldap_add( $ldap, $dn, $entry ) ) {
			echo ldap_error( $ldap );
		} else {
			echo 'Successfully added entry';
			print_r( $entry );
		}
	}
	
	ldap_close( $ldap );
}