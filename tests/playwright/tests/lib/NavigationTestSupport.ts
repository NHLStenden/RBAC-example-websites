import {expect, Page} from "@playwright/test";
import {DockerWebUser, GetRoutesInHeaderForWebsite} from "./TestUserInfo";


export async function gotoWebsiteAndTestNavigationForUser(page: Page, url: string, user: DockerWebUser): Promise<void> {
    return new Promise<void>(async (resolve, reject) => {
        try {

            await page.goto(`http://${user.username}:${user.password}@${url}`);

            const nav = GetRoutesInHeaderForWebsite(user.role);

            for (let link of nav) {
                const linkElement = await page.locator(`a[href="${link.route}"]`);
                await expect(linkElement).toBeVisible();
                await expect(linkElement).toHaveText(link.title)
            }

            resolve();
        }
        catch (error) {
            reject(error);
        }
    });
}