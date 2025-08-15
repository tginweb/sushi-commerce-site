import React, {forwardRef, useCallback, useImperativeHandle, useMemo, useRef} from 'react';
import {Platform} from 'react-native';
import Animated, {useAnimatedProps, useAnimatedStyle, useSharedValue, withTiming} from 'react-native-reanimated';
import {NativeViewGestureHandler} from 'react-native-gesture-handler';
import BottomSheetDraggableView from '../bottomSheetDraggableView';
import BottomSheetRefreshControl from '../bottomSheetRefreshControl';
import {useBottomSheetInternal, useScrollableSetter, useScrollHandler, useStableCallback,} from '../../hooks';
import {GESTURE_SOURCE, SCROLLABLE_DECELERATION_RATE_MAPPER, SCROLLABLE_STATE, SCROLLABLE_TYPE,} from '../../constants';
import {styles} from './styles';
import {FaderPosition} from "react-native-ui-lib/src/components/fader";
import {COLORS} from "~assets/design";
import {Fader} from "react-native-ui-lib";
import {ScrollableEvent} from "~ui/bottom-sheet-vendor/types";
import scrollViewIsCloseToBottom from "@core/main/util/react-native/scrollViewIsCloseToBottom";
import {useDebounceCallback} from "@core/main/lib/hooks/useDebounceCallback";
import {TScrollableChangeAction} from "@core/ui/types";

export function createBottomSheetScrollableComponent<T, P>(
    type: SCROLLABLE_TYPE,
    ScrollableComponent: any
) {
    return forwardRef<T, P>((props, ref) => {
        // props
        const {
            // hooks
            focusHook,
            scrollEventsHandlersHook,
            // props
            enableFooterMarginAdjustment = false,
            overScrollMode = 'never',
            keyboardDismissMode = 'interactive',
            showsVerticalScrollIndicator = true,
            style,
            refreshing,
            onRefresh,
            progressViewOffset,
            refreshControl,
            children,
            // events
            onScroll,
            onScrollBeginDrag,
            onScrollEndDrag,
            onContentSizeChange,
            ...rest
        }: any = props;

        //#region refs
        const nativeGestureRef = useRef<NativeViewGestureHandler>(null);
        const refreshControlGestureRef = useRef<NativeViewGestureHandler>(null);

        const scrollState = useRef<{
            layoutMeasurementHeight: number,
            contentOffsetY: number,
            contentSizeHeight: number
        }>({
            layoutMeasurementHeight: 0,
            contentOffsetY: 0,
            contentSizeHeight: 0
        });

        //#endregion

        const faderShow = useSharedValue(1);
        const faderVisibleRef = useRef<boolean>(true)
        const contentHeightRef = useRef<number>(0)

        const faderAnimatedStyles = useAnimatedStyle(() => {
            return {
                opacity: faderShow.value,
            };
        }, [faderShow.value]);


        const syncScroll = useDebounceCallback((action: TScrollableChangeAction) => {

            switch (action.type) {
                case 'scroll':
                    scrollState.current.layoutMeasurementHeight = action.event.layoutMeasurement.height
                    scrollState.current.contentOffsetY = action.event.contentOffset.y
                    scrollState.current.contentSizeHeight = action.event.contentSize.height
                    const _faderVisible = !scrollViewIsCloseToBottom(scrollState.current)
                    if (_faderVisible !== faderVisibleRef.current) {
                        faderVisibleRef.current = _faderVisible
                        faderShow.value = withTiming(_faderVisible ? 1 : 0, {duration: 100})
                    }
                    break
                case 'resize':
                    const contentHeight = action.contentHeight || scrollState.current.contentSizeHeight
                    scrollState.current.contentSizeHeight = contentHeight
                    if (scrollableRef.current?.measure) {
                        scrollableRef.current?.measure((_x, _y, _width, _height, _pageX, _pageY) => {
                            scrollState.current.layoutMeasurementHeight = _height
                            const _faderVisible = !scrollViewIsCloseToBottom(scrollState.current)
                            if (_faderVisible !== faderVisibleRef.current) {
                                faderVisibleRef.current = _faderVisible
                                faderShow.value = withTiming(_faderVisible ? 1 : 0, {duration: 100})
                            }
                        })
                    }
                    break
            }

        }, 300)

        const _onScroll = useCallback<ScrollableEvent>((event) => {
            syncScroll({type: 'scroll', event: event.nativeEvent})
            if (onScroll)
                onScroll(event)
        }, [onScroll])


        //#region hooks
        const {scrollableRef, scrollableContentOffsetY, scrollHandler} =
            useScrollHandler(
                scrollEventsHandlersHook,
                _onScroll,
                onScrollBeginDrag,
                onScrollEndDrag
            );
        const {
            enableContentPanningGesture,
            animatedFooterHeight,
            animatedScrollableState,
            animatedContentHeight,
            enableDynamicSizing,
            animatedKeyboardState,
            animatedKeyboardHeight
        } = useBottomSheetInternal();
        //#endregion

        //#region variables
        const scrollableAnimatedProps = useAnimatedProps(
            () => ({
                decelerationRate:
                    SCROLLABLE_DECELERATION_RATE_MAPPER[animatedScrollableState.value],
                showsVerticalScrollIndicator: showsVerticalScrollIndicator
                    ? animatedScrollableState.value === SCROLLABLE_STATE.UNLOCKED
                    : showsVerticalScrollIndicator,
            }),
            [showsVerticalScrollIndicator]
        );
        //#endregion


        const updateFooter = (contentHeight?: number) => {

            if (!contentHeight || contentHeightRef.current !== contentHeight)
                return;

            let offset = 0

            if (enableFooterMarginAdjustment)
                offset = animatedFooterHeight.value

            const totalContentHeight = contentHeight + offset

            if (totalContentHeight !== animatedContentHeight.value)
                animatedContentHeight.value = totalContentHeight
        }

        //#region callbacks
        const handleContentSizeChange = useStableCallback((contentWidth?: number, contentHeight?: number) => {


                if (!contentHeight && scrollState.current.contentSizeHeight) {
                    contentHeight = scrollState.current.contentSizeHeight
                }

                contentHeightRef.current = contentHeight || 0

                syncScroll({type: 'resize', contentHeight})

                if (enableDynamicSizing) {
                    setTimeout(() => updateFooter(contentHeight), 10)
                    setTimeout(() => updateFooter(contentHeight), 30)
                    setTimeout(() => updateFooter(contentHeight), 50)
                    setTimeout(() => updateFooter(contentHeight), 150)
                    setTimeout(() => updateFooter(contentHeight), 500)
                    setTimeout(() => updateFooter(contentHeight), 700)
                }

                if (onContentSizeChange && contentWidth && contentHeight) {
                    onContentSizeChange(contentWidth, contentHeight);
                }
            }
        )
        //#endregion

        //#region styles
        const containerAnimatedStyle = useAnimatedStyle(
            () => ({
                marginBottom: enableFooterMarginAdjustment
                    ? animatedFooterHeight.value
                    : 0,

            }),
            [enableFooterMarginAdjustment]
        );

        const hookStyle = useAnimatedStyle(
            () => ({
                //height: animatedKeyboardState.value === KEYBOARD_STATE.SHOWN ? animatedKeyboardHeight.value : 0
            }),
            [animatedKeyboardState.value, animatedKeyboardHeight.value]
        );

        const containerStyle = useMemo(() => {
            return enableFooterMarginAdjustment
                ? [
                    ...(style ? ('length' in style ? style : [style]) : []),
                    containerAnimatedStyle,
                ]
                : style;
        }, [enableFooterMarginAdjustment, style, containerAnimatedStyle]);

        const contentContainerStyle = {}

        //#endregion

        //#region effects
        // @ts-ignore
        useImperativeHandle(ref, () => ({
            ...scrollableRef.current,
            recalc: () => {
                setTimeout(() => {
                    handleContentSizeChange()
                }, 100)
            }
        }))

        useScrollableSetter(
            scrollableRef,
            type,
            scrollableContentOffsetY,
            onRefresh !== undefined,
            focusHook
        );
        //#endregion

        const fader = <Animated.View
                style={[
                    containerAnimatedStyle,
                    faderAnimatedStyles,
                    {
                        position: 'absolute',
                        bottom: 0,
                        left: 0,
                        width: '100%'
                    }
                ]}>
                <Fader visible={true} size={38} position={FaderPosition.BOTTOM} tintColor={COLORS.white}/>
            </Animated.View>

        //#region render
        if (Platform.OS === 'android') {
            const scrollableContent = (
                <NativeViewGestureHandler
                    ref={nativeGestureRef}
                    enabled={enableContentPanningGesture}
                    shouldCancelWhenOutside={false}
                >
                    <ScrollableComponent
                        animatedProps={scrollableAnimatedProps}
                        {...rest}
                        scrollEventThrottle={16}
                        ref={scrollableRef}
                        overScrollMode={overScrollMode}
                        keyboardDismissMode={keyboardDismissMode}
                        onScroll={scrollHandler}
                        onContentSizeChange={handleContentSizeChange}
                        style={containerStyle}
                        contentContainerStyle={contentContainerStyle}
                    >
                        {children}

                    </ScrollableComponent>
                </NativeViewGestureHandler>
            );
            return (
                <BottomSheetDraggableView
                    nativeGestureRef={nativeGestureRef}
                    refreshControlGestureRef={refreshControlGestureRef}
                    gestureType={GESTURE_SOURCE.SCROLLABLE}
                    style={styles.container}
                >
                    {onRefresh ? (
                        <BottomSheetRefreshControl
                            ref={refreshControlGestureRef}
                            refreshing={refreshing}
                            onRefresh={onRefresh}
                            progressViewOffset={progressViewOffset}
                            style={styles.container}
                        >
                            {scrollableContent}
                        </BottomSheetRefreshControl>
                    ) : (
                        scrollableContent
                    )}

                    {fader}
                </BottomSheetDraggableView>
            );
        }
        return (
            <BottomSheetDraggableView
                nativeGestureRef={nativeGestureRef}
                gestureType={GESTURE_SOURCE.SCROLLABLE}
                style={styles.container}
            >
                <NativeViewGestureHandler
                    ref={nativeGestureRef}
                    enabled={enableContentPanningGesture}
                    shouldCancelWhenOutside={false}
                >
                    <ScrollableComponent
                        animatedProps={scrollableAnimatedProps}
                        {...rest}
                        scrollEventThrottle={16}
                        ref={scrollableRef}
                        overScrollMode={overScrollMode}
                        keyboardDismissMode={keyboardDismissMode}
                        refreshing={refreshing}
                        onRefresh={onRefresh}
                        progressViewOffset={progressViewOffset}
                        refreshControl={refreshControl}
                        onScroll={scrollHandler}
                        onContentSizeChange={handleContentSizeChange}
                        style={containerStyle}
                    >
                        {children}
                    </ScrollableComponent>
                </NativeViewGestureHandler>

                {fader}

            </BottomSheetDraggableView>
        );
        //#endregion
    });
}
