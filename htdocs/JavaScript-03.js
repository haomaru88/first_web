// 모듈을 추출한다.
const express = require('express')

// 변수를 선언한다. 
const items = [{
    name:"아메리카노",
    price:"3000원"
}, {
    name:"카페라떼",
    price:"3500원"
}, {
    name:"카페모카",
    price:"3700원"
}]

// 웹 서버를 생성한다.
const app = express()
app.use(express.static('public'))

// 각각 HTML, JSON 그리고 XML 형식으로 라우트 한다.

// HTML 응답
app.get('/data.html', function(request, response){
    let output = ''
    output += '<!DOCTYPE html>'
    output += '<html>'
    output += '<head>'
    output += '     <title>DATA HTML</title>'
    output += '</head>'
    output += '<body>'
    items.forEach(function(item){
        output += '<div>'
        output += '     <h1>' + item.name + '</h1>'
        output += '     <h2>' + item.price + '</h2>'
        output += '</div>'
    })
    output += '</body>'
    output += '</html>'
    response.send(output)
})

// JSON 응답
app.get('/data.json', function(request, response){
    response.send(items)
})

// XML 응답
app.get('/data.xml', function(request, response){
    let output = ''
    output += '<?xml version="1.0" encoding="UTF-8" ?>'
    output += '<products>'
    items.forEach(function(item){
        output += '<product>'
        output += '     <name>' + item.name + '</name>'
        output += '     <price>' + item.price + '</price>'
        output += '</product>'
    })
    output += '</products>'
    response.type('text/xml')
    response.send(output)
})

// 웹 서버를 실행한다.
app.listen(52273, function(){
    console.log('Server Running at http://127.0.0.1:52273')
})