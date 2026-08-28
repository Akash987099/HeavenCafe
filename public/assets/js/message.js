function showAlert(type, title, message) {
    const colors = {
        success: {
            bg: 'bg-green-50',
            text: 'text-green-800',
            darkBg: 'dark:bg-gray-800',
            darkText: 'dark:text-green-400'
        },
        danger: {
            bg: 'bg-red-50',
            text: 'text-red-800',
            darkBg: 'dark:bg-gray-800',
            darkText: 'dark:text-red-400'
        },
        warning: {
            bg: 'bg-yellow-50',
            text: 'text-yellow-800',
            darkBg: 'dark:bg-gray-800',
            darkText: 'dark:text-yellow-300'
        },
        info: {
            bg: 'bg-blue-50',
            text: 'text-blue-800',
            darkBg: 'dark:bg-gray-800',
            darkText: 'dark:text-blue-400'
        },
        dark: {
            bg: 'bg-gray-50',
            text: 'text-gray-800',
            darkBg: 'dark:bg-gray-800',
            darkText: 'dark:text-gray-300'
        }
    };

    const alert = colors[type] || colors.info;

    const alertBox = `
            <div class="alert p-4 mb-2 text-sm rounded-lg ${alert.bg} ${alert.text} ${alert.darkBg} ${alert.darkText} shadow-md animate-fade-in-down" role="alert">
                <span class="font-medium">${title}</span> ${message}
            </div>
        `;

    const container = document.getElementById('alert-container');
    container.insertAdjacentHTML('beforeend', alertBox);

    setTimeout(() => {
        const first = container.querySelector('.alert');
        if (first) first.remove();
    }, 4000);
}
