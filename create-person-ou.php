<?php

$ldap = ldap_connect( 'ldap://twitfave.com/' );
if ( $ldap ) {
	$bind = ldap_bind( $ldap, 'cn=admin,dc=nodomain', 'mfslapd%%' );
	if ( !$bind ) {
		echo 'Failed to connect to LDAP server!';
		exit;
	}
	
	$dn = 'o=person,dc=nodomain';
	
	$entry = array();
	$entry['objectClass'] = array( 'top', 'dcObject', 'organization' );
	$entry['o'] = array( 'person' );
	$entry['dc'] = array( 'person' );
	
	if ( !ldap_add( $ldap, $dn, $entry ) ) {
		echo ldap_error( $ldap );
	}
	
	ldap_close( $ldap );
}