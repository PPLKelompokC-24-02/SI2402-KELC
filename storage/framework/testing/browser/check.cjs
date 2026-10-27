const assert=require('node:assert/strict');
const {chromium}=require('playwright');
(async()=>{
 const browser=await chromium.launch({executablePath:'C:/Program Files/Google/Chrome/Application/chrome.exe',headless:true});
 const base='http://127.0.0.1:8765'; const errors=[];
 const png=Buffer.from('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aUBsAAAAASUVORK5CYII=','base64');
 try{
  const page=await browser.newPage({viewport:{width:1440,height:1000}}); page.on('pageerror',e=>errors.push(e.message));
  await page.goto(base+'/login'); await page.locator('#email').fill('admin@careercompass.test'); await page.locator('#password').fill('CareerCompass123!');
  await Promise.all([page.waitForURL('**/home'),page.getByRole('button',{name:'Masuk ke akun'}).click()]);
  await page.locator('.sidebar-nav').getByRole('link',{name:'Manajemen Profesi',exact:true}).click();
  await page.getByRole('link',{name:'Tambah Profesi',exact:true}).click();
  await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo(0,0);}); await page.waitForTimeout(250); console.log(JSON.stringify(await page.evaluate(()=>({body:document.body.className,margin:getComputedStyle(document.querySelector('.app-main')).marginLeft,rect:document.querySelector('.app-main').getBoundingClientRect().toJSON(),form:document.querySelector('.profession-page').getBoundingClientRect().toJSON(),html:document.documentElement.clientWidth})))); await page.screenshot({path:'storage/framework/testing/browser/profession-viewport-before.png',animations:'disabled'}); await page.screenshot({path:'storage/framework/testing/browser/profession-create.png',fullPage:true,animations:'disabled'}); console.log(JSON.stringify(await page.evaluate(()=>({afterMargin:getComputedStyle(document.querySelector('.app-main')).marginLeft,afterRect:document.querySelector('.profession-page').getBoundingClientRect().toJSON(),scrollX,heading:document.querySelector('.profession-heading h1').getBoundingClientRect().toJSON()})))); await page.screenshot({path:'storage/framework/testing/browser/profession-viewport.png'});
  assert.equal(await page.locator('a[aria-current="page"]').innerText(),'Manajemen Profesi');
  const name='QA Browser '+Date.now(); await page.locator('#name').fill(name); await page.getByRole('button',{name:'Terbitkan Profesi',exact:true}).click(); assert.equal(await page.locator('#career_category_id').evaluate(e=>e.validity.valueMissing),true);
  await Promise.all([page.waitForURL(/\/admin\/professions\/\d+\/edit$/),page.getByRole('button',{name:'Simpan Draf',exact:true}).click()]);
  const editUrl=page.url(); const id=editUrl.match(/professions\/(\d+)\/edit/)[1];
  await page.locator('.profession-notice--success').waitFor();
  assert.match(await page.locator('.profession-status').innerText(),/Draf/);
  await page.locator('#career_category_id').selectOption({label:'Data & Analytics'});
  await page.locator('#summary').fill('Menjamin kualitas aplikasi dengan pengujian yang terencana.');
  await page.locator('#description').fill('QA Engineer menyusun rencana pengujian, menguji aplikasi, dan menyampaikan temuan kepada tim pengembangan.');
  await page.locator('#illustration').setInputFiles({name:'illustration.png',mimeType:'image/png',buffer:png});
  await page.locator('#entry_level').selectOption('Entry Level');
  await page.locator('#career_start').selectOption('Junior'); await page.locator('#career_end').selectOption('Senior');
  await page.locator('#industries_text').fill('Teknologi, Keuangan');
  const fillList=async(field,value)=>{const group=page.locator('[data-repeat-group="'+field+'"]');if(await group.locator('[data-repeat-rows] [data-repeat-row]').count()===0)await group.locator('[data-add-row]').click();const input=group.locator('[data-repeat-rows] input,[data-repeat-rows] select').first();if(field==='competency_ids')await input.selectOption({label:value});else await input.fill(value);};
  await fillList('prospects','Berkembang menjadi QA Lead.');
  await fillList('responsibilities','Menyusun rencana pengujian.');
  await fillList('competency_ids','Software Testing');
  const responsibility=page.locator('[data-repeat-group="responsibilities"]');
  await responsibility.locator('[data-add-row]').click(); await responsibility.locator('[data-repeat-rows] input').nth(1).fill('Menjalankan pengujian regresi.');
  await responsibility.locator('[data-add-row]').click(); await responsibility.locator('[data-repeat-rows] input').nth(2).fill('Melaporkan hasil pengujian.');
  await responsibility.locator('[data-remove-row]').nth(1).click();
  assert.equal(await responsibility.locator('[data-repeat-rows] input').nth(1).getAttribute('name'),'responsibilities[1]');
  const tools=page.locator('[data-repeat-group="tools"]'); await tools.locator('[data-add-row]').click();
  await page.locator('#tool-name-0').fill('Postman'); await page.locator('#tool-usage-0').fill('Menguji REST API.');
  await page.locator('#tool-logo-0').setInputFiles({name:'tool.png',mimeType:'image/png',buffer:png});
  await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo(0,0);}); await page.waitForTimeout(250); await page.screenshot({path:'storage/framework/testing/browser/profession-filled.png',fullPage:true});
  await Promise.all([page.waitForNavigation(),page.getByRole('button',{name:'Terbitkan Profesi',exact:true}).click()]);
  assert.equal(page.url(),editUrl);
  await page.locator('.profession-notice--success').waitFor(); assert.match(await page.locator('.profession-status').innerText(),/Terbit/);
  assert.equal(await page.locator('#tool-name-0').inputValue(),'Postman');
  assert.equal(await page.locator('[data-image-preview] img').evaluate(e=>e.complete&&e.naturalWidth>0),true);
  await page.goto(base+'/admin/professions/'+id); await page.getByText('Menguji REST API.',{exact:true}).waitFor();
  await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo(0,0);}); await page.waitForTimeout(250); await page.screenshot({path:'storage/framework/testing/browser/profession-detail.png',fullPage:true});
  await page.goto(base+'/admin/professions?keyword='+encodeURIComponent(name));
  await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo(0,0);}); await page.waitForTimeout(250); await page.screenshot({path:'storage/framework/testing/browser/profession-list.png',fullPage:true});
  await page.getByRole('button',{name:'Nonaktifkan '+name,exact:true}).click(); await page.locator('[data-deactivate-dialog]').waitFor({state:'visible'});
  await Promise.all([page.waitForNavigation(),page.locator('[data-confirm-deactivate]').click()]);
  await page.locator('#keyword').fill(name); await page.getByRole('button',{name:'Terapkan'}).click();
  assert.match(await page.locator('tbody .profession-status').innerText(),/Nonaktif/);
  await page.goto(editUrl);
  for(const width of [320,390,620,768,1024,1440]){await page.setViewportSize({width,height:900});await page.waitForTimeout(250);assert.equal(await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth),false,'overflow at '+width);}
  await page.setViewportSize({width:390,height:844}); await page.waitForTimeout(250);
  await page.evaluate(()=>{document.activeElement?.blur();window.scrollTo(0,0);}); await page.waitForTimeout(250); await page.screenshot({path:'storage/framework/testing/browser/profession-mobile.png',fullPage:true});
  await page.locator('#name').fill(name+' edited'); await page.getByRole('button',{name:'Simpan Draf',exact:true}).click();
  await page.locator('.profession-notice--success').waitFor(); assert.match(await page.locator('.profession-status').innerText(),/Draf/);
  assert.deepEqual(errors,[]);
  console.log('PASS: admin menu, draft, all low-fi fields, illustration/tool upload, repeat add/remove/reindex, publish, edit, detail, search, deactivate, and responsive widths 320–1440. No JS errors.');
 }finally{await browser.close();}
})().catch(e=>{console.error(e);process.exitCode=1;});



