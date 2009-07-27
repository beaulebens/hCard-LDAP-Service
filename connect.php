<?php

$ldap = ldap_connect( 'ldap://twitfave.com/' );
if ( $ldap ) {
	$bind = ldap_bind( $ldap, 'cn=admin,dc=nodomain', 'mfslapd%%' );
	if ( !$bind ) {
		echo 'Failed to connect to LDAP server!';
		exit;
	}
	
	$dn = 'uid=1,dc=1234,dc=nodomain';
	
	$entry = array();
	$entry['objectClass']     = array( 'top', 'person', 'organizationalPerson', 'inetOrgPerson', 'hCard' );
	$entry['cn']              = array( 'Stephen Weber' ); // Common Name
	$entry['sn']              = array( 'Weber' ); // Surname/Family Name
	$entry['gn']              = array( 'Stephen' ); // Given Name
	$entry['displayName']     = array( 'singpolyma' ); // Nickname
//	$entry['title']           = array( '' ); // Job role
	$entry['mail']            = array( 'singpolyma@singpolyma.net' ); // Email
	$entry['labeledURI']      = array( 'http://singpolyma.net' );
	$entry['mobile']          = array( '+16503957464' ); // Mobile number
	// $entry['telephoneNumber'] = array( '+14156916235' ); // Phone number
// 	$entry['postalAddress']   = array( '1408 California St, #301
// San Francisco, CA' ); // Mailing address, preformatted (homePostalAddress)
// 	$entry['postalCode']      = array( '94109' ); // ZIP
	
	if ( !ldap_add( $ldap, $dn, $entry ) ) {
		echo ldap_error( $ldap );
	} else {
		echo 'Successfully added entry';
	}
	
	ldap_close( $ldap );
}

/*
Add these from hCard/vCard
additionalName
personalTitle
honorificSuffix
bday
tz
sourceURI


NOTES:
Install OpenLDAP
Configure top-level dc (service name)
Add in dc=addressbook
Add our objectClass (hCard)

---
objectclass ( <NUMBER> NAME 'vCardPerson' SUP inetOrgPerson STRUCTURAL
	MAY ( additionalName $ personalTitle $ honorificSuffix $ bday $ tz $ sourceURI ) )
---

Can't add multiple physical addresses
Resolve different phone numbers against unique attributes
Photo/Avatar? jpegPhoto | photo?
Checkbox for get XFN crawl from sourceURI
function hcard2ldap()
*/