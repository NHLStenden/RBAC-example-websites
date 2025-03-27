FROM debian:latest

###
# Admin:  cn=admin,dc=NHLStenden,dc=com
# Password = test12345!
#
####


RUN mkdir /app
WORKDIR /app

COPY *.sh /app
RUN chmod a+x /app/*.sh

# Update repositories
RUN apt update && apt install -y debconf-utils python3 python3-venv \
    && /app/configure_ldap.sh \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y slapd ldap-utils

COPY ldif/Ldap-data-*.ldif /app
COPY ldif-base/*.base /app

RUN mkdir avatars
COPY avatars/*.jpeg avatars
COPY *.py /app


# Now start the LDAP daemon. it is not possible to execute commands after this
# So loading the LDIF-files must be done afterwards
#
ENTRYPOINT slapd -d 0
