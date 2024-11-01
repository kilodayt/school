const keywords = ['def', 'return', 'if', 'else', 'for', 'while', 'import', 'from', 'class', 'try', 'except'];

document.getElementById('codeEditor').addEventListener('input', function(event) {
    const text = event.target.value;
    const lastWord = text.split(' ').pop();

    const suggestions = keywords.filter(keyword => keyword.startsWith(lastWord));

    if (suggestions.length > 0) {
        console.log('Suggestions:', suggestions);
        // Можно вывести список подсказок в интерфейсе
    }
});
