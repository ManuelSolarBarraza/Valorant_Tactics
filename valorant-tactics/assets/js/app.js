document.querySelectorAll('form[data-confirm]').forEach(form=>form.addEventListener('submit',event=>{if(!window.confirm(form.dataset.confirm||'¿Confirmar acción?'))event.preventDefault();}));
const menu=document.querySelector('.menu-toggle'),sidebar=document.querySelector('.sidebar');if(menu&&sidebar){menu.addEventListener('click',()=>{let open=sidebar.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));});}

// Selección sugerida: estado y rol competitivo al elegir un jugador en una nueva scrim.
document.querySelectorAll('[data-player-picker]').forEach(select=>{select.addEventListener('change',()=>{
  const group=select.closest('.player-entry'),option=select.selectedOptions[0];
  if(!group||!option)return;
  const status=group.querySelector('[data-status-select]'),competitive=group.querySelector('[data-competitive-select]');
  if(status)status.value=option.dataset.status||'';
  if(competitive)competitive.value=option.dataset.competitive||'';
});});
