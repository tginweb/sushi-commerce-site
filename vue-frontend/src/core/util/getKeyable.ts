import isKeyable from "@/core/util/isKeyable";

export function getKeyable(...args: any[]) {
    return args.find(arg => isKeyable(arg))
}

export default getKeyable
