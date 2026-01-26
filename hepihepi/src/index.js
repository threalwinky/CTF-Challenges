const express = require('express')
const cookieParser = require('cookie-parser')
const { v4: uuidv4 } = require("uuid")
const lib = require('dset')

const app = express()
const PORT = 5005
const HOST = '0.0.0.0'

app.set('view engine', 'ejs')
app.set('views', __dirname + '/views')
app.use(express.static('static'))
app.use(express.json())
app.use(express.urlencoded({ extended: true }))
app.use(cookieParser())

const sessionStorage = {}

function requireSession(req, res, next) {
    const sessionID = req.cookies.session
    if (!sessionID || !sessionStorage[sessionID]) {
        return res.redirect('/')
    }
    req.session = sessionStorage[sessionID]
    req.sessionID = sessionID
    next()
}

app.get('/', (req, res) => {
    let sessionID = req.cookies.session
    if (!sessionID || !sessionStorage[sessionID]) {
        sessionID = uuidv4()
        sessionStorage[sessionID] = {
            theme: 'light'
        }
        res.cookie('session', sessionID)
    }
    members = []
    for (let i=1; i<=5; i++){
        memberID = `member${i}`
        members.push({...require(`./members/${memberID}.js`), memberID: memberID})
    }
    res.render('index', { members })
})

app.get('/member', requireSession, (req, res) => {
    memberID = req.query.memberID
    memberStats = require(`./members/${memberID}.js`)
    res.render('member', { member: memberStats })
})

app.post('/change-theme', requireSession, (req, res) => {
    const { themeVar, themeVal } = req.body
    if (typeof themeVar !== 'string' || typeof themeVal !== 'string') {
        return res.status(400).send('themeVar and themeVal must be strings')
    }
    lib.dset(sessionStorage, [[req.sessionID], themeVar], themeVal)
    res.send('Change theme successfuly')
})

app.listen(PORT, HOST, () => {
    console.log(`Server running at http://localhost:${PORT}`)
})