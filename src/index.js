const { __ } = wp.i18n;

const { createHigherOrderComponent } = wp.compose;
const { addQueryArgs } = wp.url;
const { isReusableBlock } = wp.blocks; 
const { Button } = wp.components;

import './style.scss';

/**
 * Add custom HTML to reusable blocks.
 */
 const withLockedReusableBlocks = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {

        const { attributes } = props;

        if ( props.isSelected && isReusableBlock( props ) && attributes.ref ) {
            return (
                <div className="wp-block wp-reusable-block-locked">
                    <div className="wp-reusable-block-locked__wrapper">
                        <Button
                            variant="primary"
                            className="wp-reusable-block-locked__edit-link"
                            href={ addQueryArgs( 'post.php', {
                                post: attributes.ref,
                                action: 'edit'
                            } ) }
                            target="_blank"
						    rel="noopener noreferrer"
                        >
                            { __( 'Edit Reusable Block', 'lock-reusable-blocks' ) }
                        </Button>
                    </div>
                    <BlockEdit { ...props } />
                </div>
            );
        } else {
            return <BlockEdit { ...props } />
        }

        
    };
}, 'withLockedReusableBlock' );

wp.hooks.addFilter(
    'editor.BlockEdit',
    'lock-reusable-blocks/with-locked-reusable-blocks',
    withLockedReusableBlocks
);