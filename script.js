const startButton = document.getElementById('start-btn');
const nextButton = document.getElementById('next-btn');
const questionContainerElement = document.getElementById('question-container');
const answerButtonElement = document.getElementById('answer-buttons');
const questionElement = document.createElement('div'); // Added dynamically
const scoreElement = document.getElementById('right-answers');

let shuffledQuestions, currentQuestionIndex;
let quizScore = 0;

// Add questionElement to questionContainerElement
questionContainerElement.insertBefore(questionElement, answerButtonElement);

startButton.addEventListener('click', startGame);
nextButton.addEventListener('click', () => {
  currentQuestionIndex++;
  setNextQuestion();
});

function startGame() {
  startButton.classList.add('hide');
  shuffledQuestions = questions.sort(() => Math.random() - 0.5);
  currentQuestionIndex = 0;
  questionContainerElement.classList.remove('hide');
  setNextQuestion();
  quizScore = 0;
  updateScore();
}

function setNextQuestion() {
  resetState();
  showQuestion(shuffledQuestions[currentQuestionIndex]);
}

function showQuestion(question) {
  questionElement.innerText = question.question;
  question.answers.forEach((answer) => {
    const button = document.createElement('button');
    button.innerText = answer.text;
    button.classList.add('btn');
    if (answer.correct) {
      button.dataset.correct = answer.correct;
    }
    button.addEventListener('click', selectAnswer);
    answerButtonElement.appendChild(button);
  });
}

function resetState() {
  clearStatusClass(document.body);
  nextButton.classList.add('hide');
  while (answerButtonElement.firstChild) {
    answerButtonElement.removeChild(answerButtonElement.firstChild);
  }
}

function selectAnswer(e) {
  const selectedButton = e.target;
  const correct = selectedButton.dataset.correct === "true";
  setStatusClass(document.body, correct);
  Array.from(answerButtonElement.children).forEach((button) => {
    if (button.dataset.correct === "true") {
      button.classList.add('correct');
    }
    setStatusClass(button, button.dataset.correct === "true");
  });
  if (shuffledQuestions.length > currentQuestionIndex + 1) {
    nextButton.classList.remove("hide");
  } else {
    startButton.innerText = "Restart";
    startButton.classList.remove("hide");
  }
  if (correct) {
    quizScore++;
  }
  updateScore();
}

function updateScore() {
  scoreElement.innerText = `Score: ${quizScore}`;
  scoreElement.classList.add('animate');
  setTimeout(() => {
    scoreElement.classList.remove('animate');
  }, 300); // Match duration with CSS animation
}

function setStatusClass(element, correct) {
  clearStatusClass(element);
  if (correct) {
    element.classList.add("correct");
  } else {
    element.classList.add("wrong");
  }
}

function clearStatusClass(element) {
  element.classList.remove('correct');
  element.classList.remove('wrong');
}

const questions = [
  {
    question: 'Which of these is a JavaScript Framework?',
    answers: [
      { text: 'Python', correct: false },
      { text: 'Django', correct: false },
      { text: 'React', correct: true },
      { text: 'Eclipse', correct: false }
    ],
  },
  {
    question: 'Who is the president of Nigeria?',
    answers: [
      { text: 'Bola Tinubu', correct: true },
      { text: 'Peter Obi', correct: false },
      { text: 'Buhari', correct: false },
      { text: 'Wole Soyinka', correct: false }
    ],
  },
  {
    question: 'What is 4 X 3?',
    answers: [
      { text: '24', correct: false },
      { text: '13', correct: false },
      { text: '9', correct: false },
      { text: '12', correct: true }
    ]
  }
];
