# Oefeningen

Als de Docker containers draaien is het tijd om een aantal opdrachten en oefeningen uit te voeren. Deze worden in
dit document verder uitgewerkt. De oplossingen / uitwerkingen staan aan het einde van het document.

## Opdracht 1

Login bij welke website en noteer welke mogelijkheden de gebruiker heeft. Gebruik onderstaande tabel voor de juiste
accounts. Vul bij 'Toegang' in welke functionele mogelijkheden je ziet (bijvoorbeeld in de menu balk).

| URL                       | rol       | username   | Toegang |
| --------------------------|-----------|------------|---------| 
| http://marketing.docker/  | gebruiker | `fbos`     |         | 
| http://grades.docker/     | student   | `edeboer`  |         |
| http://grades.docker/     | teacher   | `gwillems` |         |
| http://admin.docker/      | admin     | `tvisser`  |         |
| http://sharepoint.docker/ | student   | `edeboer`  |         |
| http://sharepoint.docker/ | teacher   | `gwillems` |         |

## Opdracht 2

Open de website http://marketing.docker en navigeer naar de *intranet* pagina. Er verschijnt een popup waar je moet
inloggen.

In de [README](./README.md) staan enkele accounts genoemd om mee in te loggen. Zoek een andere account in de LDAP
om mee in te loggen. Gebruik hiervoor de _Apache Directory Studio_. Het wachtwoord is altijd `Test1234!`.

**Vragen**

1. Welke eigenschap van het account heb je gebruikt om mee in te loggen?
2. Hoe kun je een account voor deze website herkennen?
2. Welke aanname doe je nu?

## Opdracht 1

Bij Marketing kan na inloggen met de user `fbos` onderstaande rechten gebruikt worden:

![opdracht 1 - 01.png](images/excersises/opdracht%201%20-%2001.png)

Bij inloggen op de admin zien we deze knoppen

![opdracht 1 - 02.png](images/excersises/opdracht%201%20-%2002.png)

# Uitwerkingen

## Opdracht 2

Door in te loggen op de LDAP-administratie kan gekeken worden naar de structuur van de account administratie.
Het personeel van Marketing is geen onderwijzend personeel dus valt onder Staff/Marketing.

![opdracht 1 - 01.png](images/excersises/opdracht%201%20-%2001.png)

De aanname is dat je verwacht dat deze groep gebruikers inderdaad bij de applicatie voor Marketing website hoort. Dat
kun je alleen zeker weten door het aan de programmeur of een website beheer te vragen of in de documentatie te kijken.

In dit geval kun je het afleiden uit de configuratie van de webserver. Kijk op Github op de volgende locatie:

* [marketing.conf](webserver/conf/marketing.conf) (webserver --> conf --> marketing.conf)

En zoek naar het onderstaande configuratie item:

```apacheconf
 Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

Daar staat netjes dat het intranet alleen beschikbaar is voor de genoemde groep. 