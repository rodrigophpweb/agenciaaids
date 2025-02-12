document.addEventListener('DOMContentLoaded', () => {
  const cells = document.querySelectorAll('.cell');
  const statusText = document.getElementById('status');
  const resetButton = document.getElementById('reset');
  let currentPlayer = 'X';
  let board = Array(9).fill(null);
  let gameActive = true;

  const winningCombinations = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
  ];

  function handleCellClick(e) {
    const cell = e.target;
    const index = cell.getAttribute('data-index');

    if (board[index] || !gameActive) return;

    board[index] = currentPlayer;
    cell.textContent = currentPlayer;

    if (checkWin()) {
      statusText.textContent = `Jogador ${currentPlayer} venceu!`;
      gameActive = false;
      return;
    }

    if (board.every(cell => cell !== null)) {
      statusText.textContent = "Empate!";
      gameActive = false;
      return;
    }

    currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
    statusText.textContent = `Turno do jogador ${currentPlayer}`;
  }

  function checkWin() {
    return winningCombinations.some(combination => {
      return combination.every(index => board[index] === currentPlayer);
    });
  }

  function resetGame() {
    board = Array(9).fill(null);
    cells.forEach(cell => cell.textContent = '');
    currentPlayer = 'X';
    gameActive = true;
    statusText.textContent = `Turno do jogador ${currentPlayer}`;
  }

  cells.forEach(cell => cell.addEventListener('click', handleCellClick));
  resetButton.addEventListener('click', resetGame);

  resetGame();
});
