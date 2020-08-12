'use strict'
const router = async ()=>{
  const route = await import('./router.js')
  return route
}
router()
