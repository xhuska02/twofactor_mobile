const openModalButtons = document.querySelectorAll('[data-modal-target]')
const closeModalButtons = document.querySelectorAll('[data-close-button]')
const dataShowQR = document.getElementById('qrButton')
const overlay = document.getElementById('overlay')

openModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modal = document.querySelector(button.dataset.modalTarget)
    openModal(modal)
  })
})

overlay.addEventListener('click', () => {
  const modals = document.querySelectorAll('.modal.active')
  modals.forEach(modal => {
    closeModal(modal)
  })
})

closeModalButtons.forEach(button => {
  button.addEventListener('click', () => {
    const modal = button.closest('.modal')
    closeModal(modal)
  })
})

function openModal(modal) {
  if (modal == null) return
  modal.classList.add('active')
  overlay.classList.add('active')
}

function closeModal(modal) {
  if (modal == null) return
  modal.classList.remove('active')
  overlay.classList.remove('active')
}

dataShowQR.addEventListener('click', () => {
  var qrCodeContainer = document.getElementById('qrCodeContainer');
  qrCodeContainer.style.display = 'block';

  // Nastavení časovače pro skrytí QR kódu po 60 vteřinách
  setTimeout(function() {
      qrCodeContainer.style.display = 'none';
  }, 60000); // 60000 milisekund = 60 vteřin
});