export function truncate (str, length) {
    var resultString = '';

    if (str.length > length) {
        resultString = str.slice(0, length) + '...';
    } else {
        resultString = str;
    }

    return resultString;
}
