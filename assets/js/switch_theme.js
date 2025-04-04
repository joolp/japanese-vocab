document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelector('#switch-theme');
    const body = document.querySelector('body');
    const links = document.querySelectorAll('a');

    const onloadTheme = localStorage.getItem('theme');

    if (onloadTheme == null || onloadTheme == 'light')
    {
        setLight();
    }
    else
    {
        setDark();
    }

    button.addEventListener('click', () => {
        if (localStorage.getItem('theme') == 'dark')
        {
            setLight();
        }
        else
        {
            setDark();
        }
    });

    function setDark(){
        localStorage.setItem('theme', 'dark');
        
        body.classList.add('dark-mode');
        button.classList.add('dark-mode');

        links.forEach((link) => {
            link.classList.add('dark-mode');
        });
    }

    function setLight(){
        localStorage.setItem('theme', 'light');
        
        body.classList.remove('dark-mode');
        button.classList.remove('dark-mode');

        links.forEach((link) => {
            link.classList.remove('dark-mode');
        });
    }
});