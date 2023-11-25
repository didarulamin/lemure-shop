<?php

if (!defined('ABSPATH')) {
    exit;
}


function digits_box_footer($style)
{

    ?>
    <div class="digits_site_footer_box">
        <?php
        if (!empty($style['dark_logo']) && !empty($style['light_logo']) && !isset($style['no_logo'])) {
            ?>
            <div class="digits_site_logo">
                <picture>
                    <?php
                    if (!empty($style['dark_logo'])) {
                        $logo = esc_attr($style['dark_logo']);
                        ?>
                        <source srcset="<?php echo esc_attr($logo); ?>" media="(prefers-color-scheme:dark)">
                        <?php
                    }
                    $logo = esc_attr($style['light_logo']);
                    ?>
                    <img src="<?php echo esc_attr($logo); ?>">
                </picture>
            </div>
            <?php
        }
        if (show_protected_by_digits()) {
            ?>
            <div class="protected_by_digits">
                <span class="protected_by_digits_text"><?php esc_attr_e('Protected by', 'digits'); ?>&nbsp;</span>
                <span class="protected_by_digits_logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17.822" height="15.543" viewBox="0 0 17.822 15.543">
                        <g id="digits-glyph" transform="translate(-283.3 -425.9)">
                            <g id="Group_394" data-name="Group 394" transform="translate(283.3 426.194)">
                                <g id="Group_393" data-name="Group 393" transform="translate(0 0)">
                                    <path id="Path_118" data-name="Path 118"
                                          d="M283.3,434.537l1.1-3.4a39.5,39.5,0,0,1,5.53,2.315c-.294-2.885-.459-4.869-.478-5.953h3.472c-.055,1.58-.239,3.546-.551,5.934a41.271,41.271,0,0,1,5.64-2.3l1.1,3.4a30.043,30.043,0,0,1-5.953,1.341,39.3,39.3,0,0,1,4.115,4.52l-2.866,2.039a59.984,59.984,0,0,1-3.27-5.144,39.6,39.6,0,0,1-3.1,5.144l-2.829-2.039a52.49,52.49,0,0,1,3.969-4.52C287.1,435.492,285.156,435.033,283.3,434.537Z"
                                          transform="translate(-283.3 -427.5)" fill="#ffc700"/>
                                </g>
                            </g>
                            <g id="Group_396" data-name="Group 396" transform="translate(284.604 425.9)">
                                <g id="Group_395" data-name="Group 395" transform="translate(0 0)">
                                    <path id="Path_119" data-name="Path 119"
                                          d="M301.791,441.443l-.165-.22c-.864-1.176-1.892-2.774-3.05-4.74a35.55,35.55,0,0,1-2.9,4.74l-.165.22-3.27-2.352.184-.22c1.58-1.948,2.793-3.344,3.619-4.171-1.874-.367-3.675-.79-5.365-1.249l-.276-.074,1.268-3.932.276.092a43.835,43.835,0,0,1,5.108,2.094c-.276-2.609-.4-4.446-.423-5.457V425.9h4.024v.276c-.037,1.47-.2,3.289-.478,5.438a40.078,40.078,0,0,1,5.218-2.076l.257-.092,1.268,3.913-.257.092a29.612,29.612,0,0,1-5.42,1.268,45.473,45.473,0,0,1,3.693,4.152l.184.22Zm-3.234-6.081.257.423c1.176,2.021,2.223,3.675,3.105,4.887l2.407-1.709a39.082,39.082,0,0,0-3.9-4.262l-.478-.4.625-.073a28.107,28.107,0,0,0,5.64-1.249l-.937-2.866a45.348,45.348,0,0,0-5.346,2.186l-.459.239.073-.514c.294-2.223.478-4.1.533-5.622h-2.9c.037,1.121.2,3.013.478,5.64l.055.533-.459-.257a35,35,0,0,0-5.218-2.2l-.919,2.848c1.745.459,3.619.882,5.549,1.268l.533.11-.4.367a47.4,47.4,0,0,0-3.748,4.262l2.37,1.709a40.979,40.979,0,0,0,2.921-4.887Z"
                                          transform="translate(-290.4 -425.9)" fill="#7e39ff"/>
                                </g>
                            </g>
                        </g>
                    </svg>
                </span>
                &nbsp;
                <span class="protected_by_digits_logo_digits">
                    <svg xmlns="http://www.w3.org/2000/svg" width="43.286" height="16.15" viewBox="0 0 43.286 16.15">
                        <path id="Union_3" data-name="Union 3"
                              d="M-663.906-4467.635h2.426a1.817,1.817,0,0,0,1.856,1.066c1.286,0,2-.716,2-1.763v-1.6h-.055a2.958,2.958,0,0,1-2.811,1.635c-2.223,0-3.693-1.708-3.693-4.557,0-2.883,1.434-4.666,3.73-4.666a2.981,2.981,0,0,1,2.81,1.726h.037v-1.58h2.444v8.985c0,2.167-1.818,3.564-4.538,3.564C-662.086-4464.861-663.7-4466.018-663.906-4467.635Zm2.26-5.218c0,1.672.771,2.628,2,2.628s2.021-.993,2.021-2.628-.791-2.7-2.021-2.7S-661.646-4474.524-661.646-4472.854Zm18.1,1.8h2.426c.11.771.716,1.213,1.708,1.213.937,0,1.507-.368,1.507-.975,0-.459-.313-.715-1.121-.9l-1.69-.368c-1.726-.386-2.609-1.285-2.609-2.627,0-1.745,1.507-2.884,3.84-2.884,2.278,0,3.766,1.157,3.8,2.921h-2.278c-.055-.735-.643-1.176-1.562-1.176-.864,0-1.415.386-1.415.974,0,.458.386.753,1.194.937l1.745.368c1.82.4,2.572,1.12,2.572,2.481,0,1.8-1.653,2.976-4.06,2.976C-641.914-4468.113-643.419-4469.29-643.549-4471.053Zm-35.166-1.8c0-2.884,1.452-4.685,3.73-4.685a2.919,2.919,0,0,1,2.774,1.708h.055v-4.721h2.48v12.255h-2.443v-1.562h-.037a2.978,2.978,0,0,1-2.829,1.708C-677.281-4468.15-678.714-4469.95-678.714-4472.854Zm2.536.018c0,1.672.771,2.7,2.021,2.7,1.231,0,2.021-1.029,2.021-2.7,0-1.653-.789-2.7-2.021-2.7h-.032C-675.42-4475.536-676.178-4474.492-676.178-4472.835Zm27.486,2.114v-4.778h-1.286v-1.874h1.286v-2.094h2.48v2.076h1.69v1.874h-1.69v4.354c0,.7.331,1.029,1.066,1.029a5.2,5.2,0,0,0,.606-.037v1.82a5.905,5.905,0,0,1-1.175.11C-647.866-4468.242-648.693-4468.958-648.693-4470.721Zm-4.8,2.442v-9.112h2.48v9.112Zm-14.5,0v-9.112h2.481v9.112Zm14.4-11.409a1.288,1.288,0,0,1,1.323-1.287,1.288,1.288,0,0,1,1.324,1.287,1.288,1.288,0,0,1-1.324,1.287A1.3,1.3,0,0,1-653.58-4479.687Zm-14.478,0a1.288,1.288,0,0,1,1.323-1.287,1.288,1.288,0,0,1,1.324,1.287,1.288,1.288,0,0,1-1.324,1.287A1.288,1.288,0,0,1-668.058-4479.687ZM-646.213-4479.467Zm0-.02v.02Z"
                              transform="translate(678.714 4480.974)" fill="#fff"/>
                    </svg>
                </span>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}

add_action('digits_box_wrapper', 'digits_box_footer');
