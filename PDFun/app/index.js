const express = require('express')
const puppeteer = require('puppeteer')
const app = express()
const redis = require('redis');

app.use(express.urlencoded({ extended: false }));
app.set('view engine', 'ejs');
app.set('views', __dirname + '/views');
app.use(express.static('static'));
const log = redis.createClient({
    url: process.env.LOG_URL || 'redis://127.0.0.1:6379'
});
log.connect();

const admin_username = process.env.ADMIN_USERNAME || 'REDACTED_USERNAME'
const admin_password = process.env.ADMIN_PASSWORD || 'REDACTED_PASSWORD'
log.set(admin_username, admin_password)

app.get('/', async(req, res) => {
    return res.render('index')
})

app.post('/login', async(req, res) => {
    const username = req.body['username'] || 'guest'
    log.set(`log_${Date.now()}`,`User ${username} logged in at ${Date()}`)
    res.redirect('/dashboard')
})

app.get('/dashboard', async(req, res) => {
    return res.render('dashboard')
})

app.post('/pdf', async(req, res) => {
    let { data } = req.body;

    if (!data) {
        return res.status(400).send('Please give me data to convert')
    }

    if (data.indexOf('proxy') != -1 || data.indexOf('http') != -1 || data.indexOf('file') != -1){
        return res.status(403).send('Forbidden')
    }

    log.set(`log_${Date.now()}`,`A user converted ${data} to PDF`)

    try{
        const browser = await puppeteer.launch({
            executablePath: '/usr/bin/google-chrome',
            args: ['--no-sandbox', '--disable-dev-shm-usage', '--disable-gpu', '--incognito', '--js-flags=--noexpose_wasm,--jitless']
        })

        const page = await browser.newPage()
        await page.setContent(data)
        const pdf = await page.pdf({
            format: 'A4',
            printBackground: true
        })
        await browser.close()
        res.contentType('application/pdf');
        return res.send(pdf)
    } catch (error) {
        console.log(error)
        return res.status(500).send('Failed')
    }
})

app.listen(3000, '0.0.0.0', () => {
    console.log('Server running on port 3000')
})