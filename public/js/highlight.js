function highlightCode() {
    const code = document.getElementById('codeEditor').value;

    // Пример: подсветка ключевых слов Python
    const keywords = ['def', 'return', 'if', 'else', 'for', 'while', 'import', 'from', 'class', 'try', 'except'];
    const operators = ['=', '==', '+', '-', '*', '/', '>', '<'];

    let highlightedCode = code;

    // Подсветка ключевых слов
    keywords.forEach(keyword => {
        const keywordRegex = new RegExp(`\\b${keyword}\\b`, 'g');
        highlightedCode = highlightedCode.replace(keywordRegex, `<span class="keyword">${keyword}</span>`);
    });

    // Подсветка операторов
    operators.forEach(operator => {
        const operatorRegex = new RegExp(`\\${operator}`, 'g');
        highlightedCode = highlightedCode.replace(operatorRegex, `<span class="operator">${operator}</span>`);
    });

    // Подсветка комментариев
    highlightedCode = highlightedCode.replace(/#(.*)/g, '<span class="comment">#$1</span>');

    document.getElementById('highlightedCode').innerHTML = highlightedCode;
}
