export default async function JSONFetchClient(url, body, method = 'GET') {
    const response = await fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        method,
        body,
    });

    if (response.ok === false) {
        throw response;
    }

    return response.json();
}
