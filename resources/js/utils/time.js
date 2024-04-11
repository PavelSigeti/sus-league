
const options = {
    year: 'numeric', month: 'numeric', day: 'numeric',
    hour: 'numeric', minute: 'numeric',
    hour12: false
};
const formatter = new Intl.DateTimeFormat('ru-RU', options);

export function time(value) {
    const date = new Date(value);
    const response = formatter.format(date).split(',');
    return `${response[0]} ${response[1]}`;
}

