(async ()=>{
  const title = await document.querySelector('title')
  const App = await document.querySelector('#app')
  const tpl = await `
  <h1>Таблица в формате Single Page Application</h1>
  <form method="post">
  <select name="oneFilter" class="oneFilter">
    <option value="columnAll">Выбрать столбец</option>
    <option value="nameProduct">Название</option>
    <option value="quantity">Количество</option>
    <option value="distance">Расстояние</option>
  </select>
  <select name="twoFilter" class="twoFilter">
    <option value="paramAll">Выбрать условие</option>
    <option value="equalTo">Равно</option>
    <option value="contains">Содержит</option>
    <option value="greaterThan">Больше</option>
    <option value="lessThan">Меньше</option>
  </select>
  <input type="text" name="text" class="textFilter" value="">
  <button class="btnFilter">Фильтровать</button>
  </form>
  <div class="table">
    <table>
    </table>
    <p class="message"></p>
  </div>
  <div class="pagination" data-page="0">
    <ul>
    </ul>
  </div>
  `
  App.innerHTML = await tpl
  const oneSelect = await document.querySelector('.oneFilter')
  const twoSelect = await document.querySelector('.twoFilter')
  const textInput = await document.querySelector('.textFilter')
  const button = await document.querySelector('.btnFilter')
  const table = await document.querySelector('table')
  const mess = await document.querySelector('.message')
  const pagination = await document.querySelector('.pagination')
  const ul = await document.querySelector('ul')

  if (sessionStorage.getItem("oneSelect")) {
    // Восстанавливаем содержимое текстового поля
    oneSelect.value = await sessionStorage.getItem("oneSelect");
  }
  if (sessionStorage.getItem("twoSelect")) {
    // Восстанавливаем содержимое текстового поля
    twoSelect.value = await sessionStorage.getItem("twoSelect"); 
  }
  if (sessionStorage.getItem("textInput")) {
    // Восстанавливаем содержимое текстового поляf
    textInput.value = await sessionStorage.getItem("textInput");
  }


  const handSelect = async (e)=>{
    for(let i=0; i<e.target.options.length; i++) {
      if(e.target.options[i].hasAttribute('selected')) {
        e.target.options[i].removeAttribute('selected')
      }
    }
    if(e.target.selectedIndex) {
      let option = await e.target.options[e.target.selectedIndex]
        option.setAttribute('selected', true)
    }
  }
  oneSelect.addEventListener('change', handSelect)
  twoSelect.addEventListener('change', handSelect)

  const handInput = async (e)=>{
    textInput.value = e.target.value
  }
  textInput.addEventListener('input', handInput)

  const handDefault = async (e)=>{
  e.preventDefault()
  if(await e.target.click && e.target != await button) {
    pagination.dataset.page = await e.target.dataset.pagin
  } else {
    pagination.dataset.page = await '0'
  }
  const data = await {
    a: oneSelect.value,
    b: twoSelect.value,
    c: textInput.value,
    d: pagination.dataset.page
  }

  sessionStorage.setItem("oneSelect", oneSelect.value);
  sessionStorage.setItem("twoSelect", twoSelect.value);
  sessionStorage.setItem("textInput", textInput.value);
  sessionStorage.setItem("pagination", pagination.dataset.page);

  const url = await '/api/?home/'
  const response = await fetch(url, {
    method: 'POST',
    body: JSON.stringify(data),
    headers: {
      'Content-Type': 'aplication/text;charset=UTF-8'
    }
  })
  if(response.ok) {
   const json = await response.json()

   title.innerHTML = await json.head.title

   if(json.content != undefined) {
    
    table.innerHTML = await ''
    mess.innerHTML = await ''
    mess.style.display = await 'none'
    ul.innerHTML = await ''
    let tplTable = await `
    <tr>
       <th>ИД</th>
       <th>Дата</th>
       <th>Название</th>
       <th>Количество</th>
       <th>Расстояние</th>  
     </tr>
    `
    for(let i = 0; i < json.content.length; i++) {
 
     tplTable += await `
       <tr>
         <td>${json.content[i].id}</td>
         <td>${json.content[i].date}</td>
         <td>${json.content[i].name_product}</td>
         <td>${json.content[i].quantity}</td>
         <td>${json.content[i].distance}</td>
       </tr>
     `
    }
    table.innerHTML += await tplTable
 
    let tplPagin = await ''
    for(let i = 0; i < json.pagination.length; i++) {
      tplPagin += await `
      <li><a href="" data-pagin="${json.pagination[i]}">${json.pagination[i] + 1}</a></li>
      `
    }
     ul.innerHTML += await tplPagin
     const li = await document.querySelectorAll('li')
     const link = await document.querySelectorAll('a')
 
 
     let sessPagin = await ''
 
     if (sessionStorage.getItem("pagination")) {
       if(link[Number(sessionStorage.getItem("pagination"))]) {
         await link[Number(sessionStorage.getItem("pagination"))].classList.add('active')
         sessPagin = await Number(sessionStorage.getItem("pagination"))
       }
     }
 
     if(link.length > 3) {
 
       for(let i = 0; i < li.length; i++) {
         li[i].style.display = await 'none'
       }
 
       li[sessPagin].style.display = await 'inline-block'
       li[li.length - 1].style.display = await 'inline-block'
       link[link.length - 1].innerHTML = await 'Next'
 
       if(link[sessPagin] && sessPagin < link.length - 1) {
         li[sessPagin + 1].style.display = await 'inline-block'
       }
 
       if(link[sessPagin] && sessPagin > 0) {
         li[sessPagin - 1].style.display = await 'inline-block'
       }
 
       if(link[sessPagin] && sessPagin >= link.length - 2) {
         link[link.length - 1].innerHTML = await link.length
       }
 
       if(link[sessPagin] && sessPagin >= 2) {
         li[0].style.display = await 'inline-block'
         link[0].innerHTML = await 'Prev'
       }
 
     }
 
     for(let i = 0; i < link.length; i++) {
       link[i].addEventListener('click', handDefault)
     }
    } else {
      table.innerHTML = await ''
      ul.innerHTML = await ''
      mess.style.display = await 'block'
      mess.innerHTML = await json.message
    }
  }
}
window.addEventListener('load', handDefault);
button.addEventListener('click', handDefault);

}) ()