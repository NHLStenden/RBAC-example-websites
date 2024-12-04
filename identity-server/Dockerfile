FROM debian:latest

###
# Admin:  cn=admin,dc=NHLStenden,dc=com
# Password = test12345!
#
####


RUN mkdir /app
WORKDIR /app

# Update repositories
RUN apt update
RUN apt install -y debconf-utils python3 python3-ldap

COPY *.sh /app

RUN chmod a+x /app/*.sh

RUN /app/configure_ldap.sh

RUN DEBIAN_FRONTEND=noninteractive apt-get install -y slapd ldap-utils

COPY ldif/Ldap-data-*.ldif /app
COPY ldif-base/*.base /app

RUN mkdir avatars
COPY avatars/*.jpeg avatars
COPY upload_avatars.py upload_avatars.py
RUN chmod a+x upload_avatars.py


# Now start the LDAP daemon. it is not possible to execute commands after this
# So loading the LDIF-files must be done afterwards
#
ENTRYPOINT slapd -d 0
