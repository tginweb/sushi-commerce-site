//@ts-ignore
import {defineBoot} from '#q-app/wrappers';
import dayjs from 'dayjs'
import isYesterday from 'dayjs/plugin/isYesterday'
import isToday from 'dayjs/plugin/isToday'
import isTomorrow from 'dayjs/plugin/isTomorrow'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import customParseFormat from 'dayjs/plugin/customParseFormat'
import duration from 'dayjs/plugin/duration'


export default defineBoot((ctx: any) => {

    dayjs.extend(utc)
    dayjs.extend(timezone)
    dayjs.extend(isYesterday)
    dayjs.extend(isToday)
    dayjs.extend(isTomorrow)
    dayjs.extend(customParseFormat)
    dayjs.extend(duration)

    dayjs.tz.setDefault('Asia/Irkutsk')

    require('dayjs/locale/ru')

    dayjs.locale('ru')
})
