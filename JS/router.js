export default (async ()=>{
	let nameModule = ''
	let search = await document.location.search
	
	switch(search) {
		
		case '':
		nameModule = await './pages/home.js'
		break
				
		default:
		nameModule = await './pages/home.js'
	}
	
	let module = await import(nameModule)	
	return module
})()