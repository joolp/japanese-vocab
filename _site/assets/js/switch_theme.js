document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('theme') == null)
    {
        localStorage.setItem('theme', 'light');
    }

    const button = document.querySelector('#switch-theme');
    const body = document.querySelector('body');
    button.addEventListener('click', () => {
        if (localStorage.getItem('theme') == 'dark')
        {
            localStorage.setItem('theme', 'light');
            body.classList.remove('dark-mode');
            button.classList.remove('dark-mode');
        }
        else if (localStorage.getItem('theme') == 'light')
        {
            localStorage.setItem('theme', 'dark');
            body.classList.add('dark-mode');
            button.classList.add('dark-mode');
        }
    });
});