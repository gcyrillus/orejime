const previousButton = document.querySelector('#prev')
const nextButton = document.querySelector('#next')
const submitButton = document.querySelector('#submit')
const tabPanels = document.querySelectorAll('.tabpanel')
const breadcrumbs = document.querySelector('#tab-status');
for (var i = 1; i < tabPanels.length; i++) {
    var span = document.createElement('span');
    span.setAttribute('class','tab');
    span.textContent = i + 1;
    breadcrumbs.append(span);
}
const tabTargets = document.querySelectorAll('.tab')
const isEmpty = (str) => !str.trim().length
let currentStep = 0
// Validate first input on load
validateEntry()

// Next: Change UI relative to current step and account for button permissions
nextButton.addEventListener('click', (event) => {
    event.preventDefault()
    
    // Hide current tab
    tabPanels[currentStep].classList.add('hidden')
    tabTargets[currentStep].classList.remove('active')
    
    // Show next tab
    tabPanels[currentStep + 1].classList.remove('hidden')
    tabTargets[currentStep + 1].classList.add('active')
    currentStep += 1
    
    validateEntry()
    updateStatusDisplay()
})

// Previous: Change UI relative to current step and account for button permissions
previousButton.addEventListener('click', (event) => {
    event.preventDefault()
    
    // Hide current tab
    tabPanels[currentStep].classList.add('hidden')
    tabTargets[currentStep].classList.remove('active')
    
    // Show previous tab
    tabPanels[currentStep - 1].classList.remove('hidden')
    tabTargets[currentStep - 1].classList.add('active')
    currentStep -= 1
    
    nextButton.removeAttribute('disabled')
    updateStatusDisplay()
})


function updateStatusDisplay() {
// If on the last step, hide the next button and show submit
if (currentStep === tabTargets.length - 1) {
nextButton.classList.add('hidden')
previousButton.classList.remove('hidden')
submitButton.classList.remove('hidden')
validateEntry()

// If it's the first step hide the previous button
} else if (currentStep == 0) {
nextButton.classList.remove('hidden')
previousButton.classList.add('hidden')
submitButton.classList.add('hidden')
// In all other instances display both buttons
} else {
nextButton.classList.remove('hidden')
previousButton.classList.remove('hidden')
submitButton.classList.add('hidden')
}
}

function validateEntry() {
let input = tabPanels[currentStep].querySelector('.form-input')

// Start but disabling continue button

if(input != null ) nextButton.setAttribute('disabled', true)
submitButton.setAttribute('disabled', true)

// Validate on initial function fire
setButtonPermissions(input)

// Validate on input
if(input != null ) input.addEventListener('input', () => setButtonPermissions(input))
// Validate if bluring from input
if(input != null ) input.addEventListener('blur', () => setButtonPermissions(input))
}

function setButtonPermissions(input) {
if (input && isEmpty(input.value)) {
nextButton.setAttribute('disabled', true)
submitButton.setAttribute('disabled', true)
} else {
nextButton.removeAttribute('disabled')
submitButton.removeAttribute('disabled')
}
}