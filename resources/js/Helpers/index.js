/**
 * Truncate the given string to the given length or based on window width.
 *
 * @return String
 */
export function truncate (str, length = null) {
    var resultString = '';
    length = length ? length : getLengthForWindowSize();

    if (str.length > length) {
        resultString = str.slice(0, length) + '...';
    } else {
        resultString = str;
    }

    return resultString;
}

/**
 * Get the length to truncate based on the current width of the window.
 *
 * @return Integer
 */
function getLengthForWindowSize () {
    const width = window.innerWidth;

    if (width >= 768 ) {
        return 50;
    }

    return 30;
}
