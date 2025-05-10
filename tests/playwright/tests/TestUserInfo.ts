
export class DockerWebUser {
    username: string;
    password: string;
    naam: string;
    dn: string;

    constructor(username: string, password: string, naam: string, dn: string) {
        this.username = username;
        this.password = password;
        this.naam = naam;
        this.dn = dn;
    }
}

export const USER_MARKETING = new DockerWebUser('dwillems','Test1234!','Daan Willems',
    'cn=Daan Willems,ou=Marketing,ou=Staff,dc=NHLStenden,dc=com'
);

export const USER_HRM = new DockerWebUser('kmulder','Test1234!','Kevin Mulder',
    'cn=Kevin Mulder,ou=HRM,ou=Staff,dc=NHLStenden,dc=com'
);

export const USER_ICT = new DockerWebUser(
    'ideboer','Test1234!','Iris de Boer','cn=Iris de Boer,ou=ICT Support,ou=Staff,dc=NHLStenden,dc=com',
);

export const USER_TEACHER = new DockerWebUser('ddekker','Test1234!','Diana Dekker','cn=Diana Dekker,ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com',)

export const ALL_TEST_USERS = [
    USER_MARKETING,USER_ICT,USER_HRM,USER_TEACHER
];
