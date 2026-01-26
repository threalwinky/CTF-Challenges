(function() {
    const themeBtn = document.getElementById('themeBtn')
    const html = document.documentElement
    let currentTheme = localStorage.getItem('theme') || 'light'
    function applyTheme(theme) {
        html.setAttribute('data-theme', theme)
        localStorage.setItem('theme', theme)
        currentTheme = theme
    }
    applyTheme(currentTheme)
    themeBtn.addEventListener('click', async () => {
        const newTheme = currentTheme === 'light' ? 'dark' : 'light'
        applyTheme(newTheme)
        
        try {
            await fetch('/change-theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    themeVar: 'theme', 
                    themeVal: newTheme 
                })
            })
        } catch (error) {
            console.log('Theme saved locally')
        }
    })
})()