import * as React from 'react';
import Svg, {Path} from 'react-native-svg';
import type {SvgProps} from 'react-native-svg';
const SvgMobile = (props: SvgProps) => (
  <Svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" {...props}>
    <Path d="M16 64C16 28.7 44.7 0 80 0h224c35.3 0 64 28.7 64 64v384c0 35.3-28.7 64-64 64H80c-35.3 0-64-28.7-64-64zm208 384a32 32 0 1 0-64 0 32 32 0 1 0 64 0m80-384H80v320h224z" />
  </Svg>
);
export default SvgMobile;
