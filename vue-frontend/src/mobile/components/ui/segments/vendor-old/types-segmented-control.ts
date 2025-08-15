import React from 'react';
import { StyleProp, ViewStyle, TextStyle } from 'react-native';
import { SegmentedControlItemProps } from './types-segment';
export type { SegmentedControlItemProps };
export type SegmentedControlProps = {
    /**
     * Array on segments.
     */
    segments?: SegmentedControlItemProps[];
    /**
     * The color of the active segment label.
     */
    activeColor?: string;
    /**
     * The color of the inactive segments (label).
     */
    inactiveColor?: string;
    /**
     * Callback for when index has change.
     */
    onChangeIndex?: (index: number) => void;
    /**
     * Initial index to be active.
     */
    initialIndex?: number;
    /**
     * The segmentedControl borderRadius
     */
    borderRadius?: number;
    /**
     * The background color of the inactive segments
     */
    backgroundColor?: string;
    /**
     * The background color of the active segment
     */
    activeBackgroundColor?: string;
    /**
     * The color of the active segment outline
     */
    outlineColor?: string;
    /**
     * The width of the active segment outline
     */
    outlineWidth?: number;
    /**
     * Should the icon be on right of the label
     */
    iconOnRight?: boolean;
    /**
     * Trailing throttle time of changing index in ms.
     */
    throttleTime?: number;
    /**
     * Additional style for the segment
     */
    segmentsStyle?: StyleProp<ViewStyle>;
    /**
     * Segment label style
     */
    segmentLabelStyle?: StyleProp<TextStyle>;
    /**
     * Additional spacing styles for the container
     */
    containerStyle?: StyleProp<ViewStyle>;
    style?: StyleProp<ViewStyle>;
    testID?: string;
    readonly?: boolean

    itemStyle?: StyleProp<ViewStyle>;
};
declare const _default: React.ForwardRefExoticComponent<SegmentedControlProps & React.RefAttributes<any>>;
export default _default;

