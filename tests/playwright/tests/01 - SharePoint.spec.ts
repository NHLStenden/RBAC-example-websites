import {test, expect, Page, chromium} from '@playwright/test';
import {ALL_TEST_USERS, DockerWebUser} from "./TestUserInfo";

const URL_SHAREPOINT = 'sharepoint.docker/intranet';

export async function gotoSharepointPageAndTestBasics(page: Page, user: DockerWebUser): Promise<void> {
    return new Promise<void>(async (resolve, reject) => {
        try {
            console.log(`- User: ${user.username}`);

            const url = `http://${user.username}:${user.password}@${URL_SHAREPOINT}`;
            await page.goto(url);

            await page.getByRole('link', {name: 'Mijn gegevens'}).click();

            await expect(page.locator('td.value.DistinguishedName')).toHaveText(user.dn)
            await expect(page.locator('td.value.Volledigenaam')).toHaveText(user.naam)
            await expect(page.locator('td.value.Username')).toHaveText(user.username)

            await page.getByRole('link', {name: 'Medewerkersportaal'}).click();

            await expect(page.getByText('Declareren')).toBeVisible();
            await expect(page.getByText('Contact HR')).toBeVisible();
            await expect(page.getByText('Onboarding')).toBeVisible();

            resolve();
        } catch (error) {
            reject(error);
        }
    });
}

test('test', async ({page}) => {
    const browser = await chromium.launch({headless: true});

    for (let user of ALL_TEST_USERS) {
        const context = await browser.newContext({
            httpCredentials: {
                username: user.username,
                password: user.password,
            },
        });

        const page = await context.newPage();
        await gotoSharepointPageAndTestBasics(page, user);
        await context.close();
    }
    await browser.close();
});
