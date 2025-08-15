export default function getCashOptions(sumOrder: number | null | undefined) {

    if (!sumOrder)
        return []

    const variantsRound = [
        100,
        500,
        1000,
        5000,
    ];

    const resultArray = [];

    for (let i = 0; i < variantsRound.length; i += 1) {
        const roundVariant = variantsRound[i];

        if (roundVariant !== 0) {
            const ost = sumOrder % roundVariant;
            let add = 0;
            if (ost > 0) {
                add = roundVariant - (sumOrder % roundVariant);
            }
            const roundSum = sumOrder + add;

            const searchIndex = resultArray.findIndex((item) => item === roundSum);

            if (searchIndex === -1) {
                resultArray.push(roundSum);
            }
        } else {
            resultArray.push(sumOrder);
        }
    }

    const res = [
        {
            NAME: 'Без сдачи',
            VALUE: -1
        },
        ...resultArray.map(item => ({
            NAME: item,
            VALUE: item,
        }))
    ]

    return res
}
