(() => {
'use strict';
const dialog = document.querySelector('#work-dialog');
let opener;
if (dialog && typeof dialog.showModal === 'function') {
 document.querySelectorAll('[data-work-target]').forEach(button => button.addEventListener('click', () => {
  const template = document.getElementById(button.dataset.workTarget);
  if (!template) return;
  opener = button;
  dialog.querySelector('.modal-body').replaceChildren(template.content.cloneNode(true));
  dialog.showModal(); document.body.classList.add('modal-open');
 }));
 dialog.querySelector('.dialog-close').addEventListener('click', () => dialog.close());
 dialog.addEventListener('click', event => {
  const r = dialog.getBoundingClientRect();
  if (event.target === dialog && (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom)) dialog.close();
 });
 dialog.addEventListener('close', () => {document.body.classList.remove('modal-open');opener?.focus();});
}
const top = document.querySelector('.to-top');
if (top) {
 const update = () => {top.hidden = window.scrollY < 200;};
 window.addEventListener('scroll', update, {passive:true});update();
 top.addEventListener('click', () => {window.scrollTo({top:0,behavior:matchMedia('(prefers-reduced-motion: reduce)').matches?'instant':'smooth'});document.querySelector('.brand')?.focus({preventScroll:true});});
}
document.querySelector('[data-demo-form]')?.addEventListener('submit', event => {event.preventDefault();document.querySelector('#demo-status').textContent='静的版では送信しません。WordPress版でお問い合わせフォームを設定してください。';});
})();
