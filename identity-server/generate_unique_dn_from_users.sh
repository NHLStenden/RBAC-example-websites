cp role_assignment_mail.base role_assignment_mail.ldif
grep 'dn:' Ldap-data-[0-9]*users*.ldif | awk -F \: '//{print "uniqueMember:" $2}' >> role_assignment_mail.ldif
