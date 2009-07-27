<?php
require_once 'hkit.class.php';

/*
$urls = array();

// Grab page and get all external URLs
$html = file_get_contents( 'http://microformats.org/wiki/hcard-examples-in-wild' );
preg_match_all( '/<a href[^>]+/is', $html, $hrefs );
foreach( $hrefs[0] as $href ) {
	if ( stristr( $href, 'class="external' ) ) {
		preg_match( '/href="([^"]+)"/is', $href, $bits );
		$urls[] = $bits[1];
	}
}
*/

// Let's just work on a random slice of the URLs
$urls = array( 'http://dentedreality.com.au/contact/' );

// Now go through each URL and throw against Plaxo to convert to JSON
foreach( $urls as $url ) {
	// Get the URL and parse for hCards
	$hKit = new hKit();
	$hKit->tidy_mode = 'exec';
	$hKit->tmp_dir = '/tmp/';
	$hCards = $hKit->getByURL( 'hcard', $url );

	// Loop through hCards found and stick them in LDAP
	foreach( $hCards as $hCard ) {
		if ( empty( $hCard['fn'] ) && empty( $hCard['n']['given-name'] ) && empty( $hCard['n']['family-name'] ) )
			continue; // Can't do anything without a name of some sort
			
		// Compile details from card read to create LDAP entry
		$dn = 'cn=' . $hCard['fn'] . ', o=SuperMegaScrapingAddressBook, c=US';
		$entry = array();
		$entry['cn']              = array( '' ); // Common Name
		$entry['sn']              = array( '' ); // Surname/Family Name
		$entry['gn']              = array( '' ); // Given Name
		$entry['personalTitle']   = array( '' ); // Mr, Mrs etc		
		$entry['displayName']     = array( '' ); // Nickname
		$entry['title']           = array( '' ); // Job role
		$entry['jpegPhoto']       = array( '' ); // Avatar
		$entry['mail']            = array( '' ); // Email
		$entry['telephoneNumber'] = array( '' ); // Phone number
		$entry['mobile']          = array( '' ); // Mobile number
		$entry['postalAddress']   = array( '' ); // Mailing address, preformatted
		$entry['postalCode']      = array( '' ); // ZIP
		$enrty['ou']              = array( '' ); // Organizational Unit (department?)
		
		ldap_add();
	}
}
