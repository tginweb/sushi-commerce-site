export function isKeyable(value: any) {
    var type = typeof (value);
    if (type === 'string') return true;
    if (type === 'number') return true;
    return false;
}

export default isKeyable
