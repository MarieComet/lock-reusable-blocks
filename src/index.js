import { __ } from '@wordpress/i18n';
import { createHigherOrderComponent } from '@wordpress/compose';
import { addQueryArgs } from '@wordpress/url';
import { isReusableBlock } from '@wordpress/blocks';
import { Button } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import { store as reusableBlocksStore } from '@wordpress/reusable-blocks';
import { addFilter } from '@wordpress/hooks';

import './style.scss';

/**
 * Add custom HTML to reusable blocks.
 */
const withLockedReusableBlocks = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        const { attributes, clientId } = props;

        if ( props.isSelected && isReusableBlock( props ) && attributes.ref ) {
            let showConvert = true,
                showEdit = true;
            if ( typeof lrb !== 'undefined' ) {
                if (
                    lrb.hide_convert_button &&
					lrb.hide_convert_button === '1'
                ) {
                    showConvert = false;
                }
                if ( lrb.hide_edit_button && lrb.hide_edit_button === '1' ) {
                    showEdit = false;
                }
            }

            const { __experimentalConvertBlockToStatic: convertBlockToStatic } =
				useDispatch( reusableBlocksStore );

            return (
                <div className="wp-block wp-reusable-block-locked">
                    <div className="wp-reusable-block-locked__wrapper">
                        { showEdit && (
                            <Button
                                variant="primary"
                                className="wp-reusable-block-locked__edit-link"
                                href={ addQueryArgs( 'post.php', {
                                    post: attributes.ref,
                                    action: 'edit',
                                } ) }
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                { __(
                                    'Edit reusable block',
                                    'lock-reusable-blocks',
                                ) }
                            </Button>
                        ) }
                        { showConvert && (
                            <Button
                                onClick={ () =>
                                    convertBlockToStatic( clientId )
                                }
                                variant="primary"
                                className="wp-reusable-block-locked__convert-link"
                            >
                                { __(
                                    'Convert to regular blocks',
                                    'lock-reusable-blocks',
                                ) }
                            </Button>
                        ) }
                    </div>
                    <BlockEdit { ...props } />
                </div>
            );
        }
        return <BlockEdit { ...props } />;
    };
}, 'withLockedReusableBlock' );

addFilter(
    'editor.BlockEdit',
    'lock-reusable-blocks/with-locked-reusable-blocks',
    withLockedReusableBlocks,
);
