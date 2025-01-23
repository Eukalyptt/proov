document.addEventListener("DOMContentLoaded", () => {
    async function fetchMatkasportData(type) {
        try {
            const response = await fetch(`/backend/api/crawl.php?type=${type}`); // kust andmeid võtab
            const data = await response.json();

            const dashboard = document.getElementById('dashboard');
            dashboard.innerHTML = ''; // tühjendab olemasoleva sisu et hakata uut peale panema

            if (type === 'categories') {
                data.categories.forEach(category => {
                    const div = document.createElement('div');
                    div.classList.add('card');
                    div.innerHTML = `
                        <p>${category.category}</p>
                    `;
                    dashboard.appendChild(div);
                });
            } else {
                data.products.forEach(product => {
                    const div = document.createElement('div');
                    div.classList.add('card');
                    div.innerHTML = `
                        <h3>${product.name}</h3>
                    `;
                    dashboard.appendChild(div);
                });
            }
        } catch (error) {
            console.error("Viga andmete laadimisel:", error);
            alert('Error fetching data');
        }
    }

    // Nuppude käsitlejad
    document.getElementById('fetch-data').addEventListener('click', () => fetchMatkasportData('products'));
    document.getElementById('fetch-all-categories').addEventListener('click', () => fetchMatkasportData('categories'));
});
