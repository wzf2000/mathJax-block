(function (wp) {
    function escape2Html(str) {
        var arrEntities = {
            'lt': '<',
            'gt': '>',
            'nbsp': ' ',
            'amp': '&',
            'quot': '"'
        };
        return str.replace(/&(lt|gt|nbsp|amp|quot);/ig, function(all, t) {
            return arrEntities[t];
        });
    }
    wp.blocks.registerBlockType('mathjax-block/mathjax-display', {
        apiversion: 2,
        title: 'mathJax行间公式',
        description: '用于行间数学公式的展示',
        category: 'mathJax-block',
        icon: 'plus',
        keywords: [ 'math' ],
        attributes: {
            content: {
                type: 'string',
                source: 'html',
                selector: 'span.mathjax-display-block-code'
            },
            math_content: {
                type: 'string',
                source: 'html',
                selector: 'span.mathjax-display-block-show'
            }
        },
        edit: function (props) {
            var blockProps = wp.blockEditor.useBlockProps({
                className: 'mathjax-display-block'
            });
            let content = escape2Html(props.attributes.content);
            let math_content = escape2Html(props.attributes.math_content);
            function setContent( event ) {
                props.setAttributes({
                    content: event.target.value,
                    math_content: "$$" + event.target.value + "$$"
                });
                event.preventDefault();
            }
            function genMath( event ) {
                console.log('change it!');
            }
            textArea = wp.element.createElement(
                'textarea', {
                    onChange: setContent,
                    placeholder: '请输入你的数学公式',
                    rows: 4,
                    style: {
                        width: '100%',
                        border: '0',
                        borderRadius: '5px',
                        backgroundColor: 'rgba(241,241,241,0.98)',
                        padding: '10px',
                        resize: 'none'
                    }
                },
                content
            );
            display = wp.element.createElement(
                'span', {
                    className: 'mathjax-display-block-show',
                    onChange: genMath
                    // style: {}
                },
                math_content
            );
            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            return wp.element.createElement(
                'div',
                Object.assign(blockProps, {
                    style: {}
                }),
                [textArea, display]
            );
        },
        save: function (props) {
            var blockProps = wp.blockEditor.useBlockProps.save();
            let content = escape2Html(props.attributes.content);
            let math_content = escape2Html(props.attributes.math_content);
            console.log(content);
            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            return wp.element.createElement(
                'div',
                blockProps, [
                    wp.element.createElement(
                        'span',
                        { className: 'mathjax-display-block-show' },
                        math_content
                    ),
                    wp.element.createElement(
                        'span',
                        { hidden: true, className: 'mathjax-display-block-code' },
                        content
                    ),
                ]
            );
        }
    });
})(window.wp);