const RANDOM_QUOTE_API_URL = 'https://api.quotable.io/random';


let seconds = 0;
let timerInterval;
let quotepoints = 0;
let letterspoints = 0;
let isGameStarted = false;
let quoteactual = 0;
let highScore = 0;
let isResultSaved = false;


let timerElement, containergame, button, quoteDisplayElement, quoteInputElement, DivResults, ButtonRestart;


function waitForElement(selector, isId = true) {
  return new Promise((resolve) => {
    const checkElement = () => {
      const element = isId ? document.getElementById(selector) : document.querySelector(selector);
      if (element) {
        resolve(element);
      } else {
        setTimeout(checkElement, 50);
      }
    };
    checkElement();
  });
}


window.addEventListener('load', async function() {
  console.log('Página totalmente carregada, iniciando configuração...');
  
  try {
   
    console.log('Aguardando elementos...');
    
    timerElement = await waitForElement('timer');
    console.log('Timer encontrado');
    
    containergame = await waitForElement('.containerJogo', false);
    console.log('Container game encontrado');
    
    button = await waitForElement('button');
    console.log('Button encontrado');
    
    quoteDisplayElement = await waitForElement('quoteDisplay');
    console.log('Quote display encontrado');
    
    quoteInputElement = await waitForElement('quoteInput');
    console.log('Quote input encontrado');
    
    DivResults = await waitForElement('divResults');
    console.log('Div results encontrado');
    
    ButtonRestart = await waitForElement('ButtonRestart');
    console.log('Button restart encontrado');
    
    console.log('Todos os elementos encontrados! Configurando event listeners...');

   
    button.addEventListener('click', function() {
      console.log('Botão JOGAR clicado!');
      try {
  
        const content = document.getElementById('content');
        const timerStructure = document.getElementById('timer_structure');
        
        if (content) content.style.display = 'none';
        if (timerStructure) timerStructure.style.display = 'block';
        if (containergame) containergame.style.display = "block";
        if (DivResults) DivResults.style.display = "none";
        
        if (!isGameStarted) {
          startGame();
        }
      } catch (error) {
        console.error('Erro ao iniciar jogo:', error);
      }
    });

   
    ButtonRestart.addEventListener('click', function() {
      console.log('Botão RESTART clicado!');
      try {
     
        const content = document.getElementById('content');
        const timerStructure = document.getElementById('timer_structure');
        
        if (content) content.style.display = 'none';
        if (timerStructure) timerStructure.style.display = 'block';
        if (containergame) containergame.style.display = "block";
        if (DivResults) DivResults.style.display = "none";
        
        if (!isGameStarted) {
          startGame();
        }
      } catch (error) {
        console.error('Erro ao reiniciar jogo:', error);
      }
    });

    quoteInputElement.addEventListener('input', function() {
      try {
        const arrayQuote = quoteDisplayElement.querySelectorAll('span');
        const arrayValue = quoteInputElement.value.split('');

        arrayQuote.forEach((characterSpan, index) => {
          const character = arrayValue[index];
          if (character == null) {
            characterSpan.classList.remove('correct');
            characterSpan.classList.remove('incorrect');
          } else if (character === characterSpan.innerText) {
            characterSpan.classList.add('correct');
            characterSpan.classList.remove('incorrect');
          } else {
            characterSpan.classList.remove('correct');
            characterSpan.classList.add('incorrect');
          }
        });

        const isComplete = arrayValue.length === arrayQuote.length;
        quoteactual = arrayValue.length;
        if (isComplete) {
          
          const pontosGanhos = arrayValue.length * 10;
          letterspoints = letterspoints + pontosGanhos;
          console.log(`Frase completada! ${arrayValue.length} letras = ${pontosGanhos} pontos. Total: ${letterspoints}`);
          quotepoints++;
          renderNewQuote();
        }
      } catch (error) {
        console.error('Erro no input de digitação:', error);
      }
    });
    
    console.log('Todos os event listeners configurados com sucesso!');
    
   
    console.log('Testando conectividade com API...');
    getRandomQuote().then(quote => {
      console.log('API funcionando! Frase de teste:', quote);
    }).catch(error => {
      console.error('Problema com a API:', error);
    });
    
  } catch (error) {
    console.error('Erro ao inicializar o jogo:', error);
  }
});

function getRandomQuote() {
  console.log('Buscando frase da API...');
  return fetch(RANDOM_QUOTE_API_URL)
    .then(response => {
      console.log('Resposta da API recebida:', response);
      return response.json();
    })
    .then(data => {
      console.log('Dados da API:', data);
      return data.content;
    })
    .catch(error => {
      console.error('Erro ao buscar frase:', error);
      return 'Frase de exemplo para testar o jogo de digitação. Digite esta frase para ver como funciona.';
    });
}

async function renderNewQuote() {
  console.log('Renderizando nova frase...');
  try {
    if (!quoteDisplayElement) {
      console.error('quoteDisplayElement não encontrado!');
      return;
    }
    
    const quote = await getRandomQuote();
    console.log('Frase recebida:', quote);
    
    quoteDisplayElement.innerHTML = '';
    quote.split('').forEach(character => {
      const characterSpan = document.createElement('span');
      characterSpan.innerText = character;
      quoteDisplayElement.appendChild(characterSpan);
    });
    
    console.log('Frase renderizada no DOM');
    
    if (quoteInputElement) {
      quoteInputElement.value = '';
      quoteInputElement.focus();
    }
  } catch (error) {
    console.error('Erro ao renderizar frase:', error);
    
    if (quoteDisplayElement) {
      quoteDisplayElement.innerHTML = 'Erro ao carregar frase. Tente novamente.';
    }
  }
}

let startTime;

function startGame() {
  isGameStarted = true;
  seconds = 0;
  letterspoints = 0
  renderNewQuote();
  startTimer();
}

function startTimer() {
  timerElement.innerText = seconds;
  startTime = new Date();
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {

    timerElement.innerText = getTimerTime();

    timerElement.style.color = '#ff66b2';

    if (seconds >= 20) {
      if (seconds % 2 === 0) {
        timerElement.style.color = 'red';
      } else {
        timerElement.style.color = '#ff66b2';
      }
    }
    if (seconds === 30) {
      clearInterval(timerInterval);
      
    
      if (containergame) containergame.style.display = 'none';
      
      const timerStructure = document.getElementById('timer_structure');
      if (timerStructure) timerStructure.style.display = 'none';
      
      if (timerElement) timerElement.innerText = '';
      if (quoteDisplayElement) quoteDisplayElement.innerHTML = '';
      
     
      const pontosFinais = quoteactual * 10;
      letterspoints = letterspoints + pontosFinais;
      console.log(`Fim do jogo! ${quoteactual} letras da frase atual = ${pontosFinais} pontos. Pontuação final: ${letterspoints}`);
      
      if (letterspoints > highScore) {
        highScore = letterspoints;
      }
      
   
      if (DivResults) DivResults.style.display = "block";
      
      const PgameResult = document.getElementById('matchResult');
      if (PgameResult) {
        PgameResult.innerHTML = `${letterspoints}`;
        console.log('Pontuação exibida:', letterspoints);
      } else {
        console.error('Elemento matchResult não encontrado!');
      }
      
   
      console.log('Salvando pontuação:', letterspoints);
      $.ajax({
        type: "POST",
        url: "../pages/jogo.php",
        data: {
          letterspoints: letterspoints
        },
        dataType: 'json',
        success: function (response) {
          console.log('Resposta do servidor:', response);
          if (response.success) {
            console.log('Pontuação salva com sucesso!', response.message);
          } else {
            console.error('Erro ao salvar pontuação:', response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error('Erro na requisição AJAX:', error);
          console.error('Status:', status);
          console.error('Response:', xhr.responseText);
        }
      });
      isGameStarted = false;
    }
  }, 1000);
}

function getTimerTime() {
  seconds++;
  return Math.floor((new Date() - startTime) / 1000);
}

document.addEventListener('DOMContentLoaded', function() {
  const content = document.getElementById('content');
  const timerStructure = document.getElementById('timer_structure');
  const containergame = document.querySelector('.containerJogo');
  const divResults = document.getElementById('divResults');
  const button = document.getElementById('button');
  const buttonRestart = document.getElementById('ButtonRestart');

  
  button.addEventListener('click', function() {
    content.style.display = 'none';
    timerStructure.style.display = 'block';
    containergame.style.display = 'block';
    divResults.style.display = 'none';
 
  });
  buttonRestart.addEventListener('click', function() {
    content.style.display = 'none';
    timerStructure.style.display = 'block';
    containergame.style.display = 'block';
    divResults.style.display = 'none';
    
  });


  function mostrarResultado() {const RANDOM_QUOTE_API_URL = 'https://api.quotable.io/random';

   
    let seconds = 0;
    let timerInterval;
    let quotepoints = 0;
    let letterspoints = 0;
    let isGameStarted = false;
    let quoteactual = 0;
    let highScore = 0;
    let isResultSaved = false;
    
    
    let timerElement, containergame, button, quoteDisplayElement, quoteInputElement, DivResults, ButtonRestart;
    
    
    function waitForElement(selector, isId = true) {
      return new Promise((resolve) => {
        const checkElement = () => {
          const element = isId ? document.getElementById(selector) : document.querySelector(selector);
          if (element) {
            resolve(element);
          } else {
            setTimeout(checkElement, 50);
          }
        };
        checkElement();
      });
    }
    
   
    window.addEventListener('load', async function() {
      console.log('Página totalmente carregada, iniciando configuração...');
      
      try {
       
        console.log('Aguardando elementos...');
        
        timerElement = await waitForElement('timer');
        console.log('Timer encontrado');
        
        containergame = await waitForElement('.containerJogo', false);
        console.log('Container game encontrado');
        
        button = await waitForElement('button');
        console.log('Button encontrado');
        
        quoteDisplayElement = await waitForElement('quoteDisplay');
        console.log('Quote display encontrado');
        
        quoteInputElement = await waitForElement('quoteInput');
        console.log('Quote input encontrado');
        
        DivResults = await waitForElement('divResults');
        console.log('Div results encontrado');
        
        ButtonRestart = await waitForElement('ButtonRestart');
        console.log('Button restart encontrado');
        
        console.log('Todos os elementos encontrados! Configurando event listeners...');
    
        
        button.addEventListener('click', function() {
          console.log('Botão JOGAR clicado!');
          try {
            
            const content = document.getElementById('content');
            const timerStructure = document.getElementById('timer_structure');
            
            if (content) content.style.display = 'none';
            if (timerStructure) timerStructure.style.display = 'block';
            if (containergame) containergame.style.display = "block";
            if (DivResults) DivResults.style.display = "none";
            
            if (!isGameStarted) {
              startGame();
            }
          } catch (error) {
            console.error('Erro ao iniciar jogo:', error);
          }
        });
    
    
        ButtonRestart.addEventListener('click', function() {
          console.log('Botão RESTART clicado!');
          try {
           
            const content = document.getElementById('content');
            const timerStructure = document.getElementById('timer_structure');
            
            if (content) content.style.display = 'none';
            if (timerStructure) timerStructure.style.display = 'block';
            if (containergame) containergame.style.display = "block";
            if (DivResults) DivResults.style.display = "none";
            
            if (!isGameStarted) {
              startGame();
            }
          } catch (error) {
            console.error('Erro ao reiniciar jogo:', error);
          }
        });
    
        
        quoteInputElement.addEventListener('input', function() {
          try {
            const arrayQuote = quoteDisplayElement.querySelectorAll('span');
            const arrayValue = quoteInputElement.value.split('');
    
            arrayQuote.forEach((characterSpan, index) => {
              const character = arrayValue[index];
              if (character == null) {
                characterSpan.classList.remove('correct');
                characterSpan.classList.remove('incorrect');
              } else if (character === characterSpan.innerText) {
                characterSpan.classList.add('correct');
                characterSpan.classList.remove('incorrect');
              } else {
                characterSpan.classList.remove('correct');
                characterSpan.classList.add('incorrect');
              }
            });
    
            const isComplete = arrayValue.length === arrayQuote.length;
            quoteactual = arrayValue.length;
            if (isComplete) {
              
              const pontosGanhos = arrayValue.length * 10;
              letterspoints = letterspoints + pontosGanhos;
              console.log(`Frase completada! ${arrayValue.length} letras = ${pontosGanhos} pontos. Total: ${letterspoints}`);
              quotepoints++;
              renderNewQuote();
            }
          } catch (error) {
            console.error('Erro no input de digitação:', error);
          }
        });
        
        console.log('Todos os event listeners configurados com sucesso!');
        
        
        console.log('Testando conectividade com API...');
        getRandomQuote().then(quote => {
          console.log('API funcionando! Frase de teste:', quote);
        }).catch(error => {
          console.error('Problema com a API:', error);
        });
        
      } catch (error) {
        console.error('Erro ao inicializar o jogo:', error);
      }
    });
    
    function getRandomQuote() {
      console.log('Buscando frase da API...');
      return fetch(RANDOM_QUOTE_API_URL)
        .then(response => {
          console.log('Resposta da API recebida:', response);
          return response.json();
        })
        .then(data => {
          console.log('Dados da API:', data);
          return data.content;
        })
        .catch(error => {
          console.error('Erro ao buscar frase:', error);
          return 'Frase de exemplo para testar o jogo de digitação. Digite esta frase para ver como funciona.';
        });
    }
    
    async function renderNewQuote() {
      console.log('Renderizando nova frase...');
      try {
        if (!quoteDisplayElement) {
          console.error('quoteDisplayElement não encontrado!');
          return;
        }
        
        const quote = await getRandomQuote();
        console.log('Frase recebida:', quote);
        
        quoteDisplayElement.innerHTML = '';
        quote.split('').forEach(character => {
          const characterSpan = document.createElement('span');
          characterSpan.innerText = character;
          quoteDisplayElement.appendChild(characterSpan);
        });
        
        console.log('Frase renderizada no DOM');
        
        if (quoteInputElement) {
          quoteInputElement.value = '';
          quoteInputElement.focus();
        }
      } catch (error) {
        console.error('Erro ao renderizar frase:', error);
        
        if (quoteDisplayElement) {
          quoteDisplayElement.innerHTML = 'Erro ao carregar frase. Tente novamente.';
        }
      }
    }
    
    let startTime;
    
    function startGame() {
      isGameStarted = true;
      seconds = 0;
      letterspoints = 0
      renderNewQuote();
      startTimer();
    }
    
    function startTimer() {
      timerElement.innerText = seconds;
      startTime = new Date();
      clearInterval(timerInterval);
      timerInterval = setInterval(() => {
    
        timerElement.innerText = getTimerTime();
    
        timerElement.style.color = '#ff66b2';
    
        if (seconds >= 20) {
          if (seconds % 2 === 0) {
            timerElement.style.color = 'red';
          } else {
            timerElement.style.color = '#ff66b2';
          }
        }
        if (seconds === 30) {
          clearInterval(timerInterval);
          
          
          if (containergame) containergame.style.display = 'none';
          
          const timerStructure = document.getElementById('timer_structure');
          if (timerStructure) timerStructure.style.display = 'none';
          
          if (timerElement) timerElement.innerText = '';
          if (quoteDisplayElement) quoteDisplayElement.innerHTML = '';
          
         
          const pontosFinais = quoteactual * 10;
          letterspoints = letterspoints + pontosFinais;
          console.log(`Fim do jogo! ${quoteactual} letras da frase atual = ${pontosFinais} pontos. Pontuação final: ${letterspoints}`);
          
          if (letterspoints > highScore) {
            highScore = letterspoints;
          }
          
        
          if (DivResults) DivResults.style.display = "block";
          
          const PgameResult = document.getElementById('matchResult');
          if (PgameResult) {
            PgameResult.innerHTML = `${letterspoints}`;
            console.log('Pontuação exibida:', letterspoints);
          } else {
            console.error('Elemento matchResult não encontrado!');
          }
          
       
          console.log('Salvando pontuação:', letterspoints);
          $.ajax({
            type: "POST",
            url: "../pages/jogo.php",
            data: {
              letterspoints: letterspoints
            },
            dataType: 'json',
            success: function (response) {
              console.log('Resposta do servidor:', response);
              if (response.success) {
                console.log('Pontuação salva com sucesso!', response.message);
              } else {
                console.error('Erro ao salvar pontuação:', response.message);
              }
            },
            error: function(xhr, status, error) {
              console.error('Erro na requisição AJAX:', error);
              console.error('Status:', status);
              console.error('Response:', xhr.responseText);
            }
          });
          isGameStarted = false;
        }
      }, 1000);
    }
    
    function getTimerTime() {
      seconds++;
      return Math.floor((new Date() - startTime) / 1000);
    }
    content.style.display = 'none';
    timerStructure.style.display = 'none';
    containergame.style.display = 'none';
    divResults.style.display = 'block';
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const button = document.getElementById('button');
  const timerStructure = document.getElementById('timer_structure');
  const timerElement = document.getElementById('timer');
  const content = document.getElementById('content');

  button.addEventListener('click', function() {
    content.style.display = 'none';
    timerStructure.style.display = 'block';
    let timeLeft = 60;
    timerElement.innerHTML = timeLeft;
    const interval = setInterval(function() {
      timeLeft--;
      timerElement.innerHTML = timeLeft;
      if (timeLeft <= 0) {
        clearInterval(interval);
        timerElement.innerHTML = "Tempo esgotado!";
      }
    }, 1000);
  });
});