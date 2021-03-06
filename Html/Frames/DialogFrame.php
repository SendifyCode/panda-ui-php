<?php

/*
 * This file is part of the Panda framework Ui component.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Ui\Html\Frames;

use Panda\Ui\Contracts\Factories\HTMLFactoryInterface;
use Panda\Ui\Contracts\Factories\HTMLFormFactoryInterface;
use Panda\Ui\Html\HTMLDocument;
use Panda\Ui\Html\Templates\Forms\SimpleForm;

/**
 * Window Dialog Frame
 * Creates a dialog frame popup to display content to the user and perform an action.
 *
 * @package Panda\Ui\Html\Frames
 *
 * @version 0.1
 */
class DialogFrame extends WindowFrame
{
    /**
     * OK/Cancel dialog buttons.
     *
     * @var string
     */
    const TYPE_OK_CANCEL = '1';

    /**
     * Yes/No dialog buttons.
     *
     * @var string
     */
    const TYPE_YES_NO = '2';

    /**
     * @var SimpleForm
     */
    private $form;

    /**
     * dialogFrame constructor.
     *
     * @param HTMLDocument             $HTMLDocument
     * @param HTMLFormFactoryInterface $FormFactory
     */
    public function __construct(HTMLDocument $HTMLDocument, HTMLFormFactoryInterface $FormFactory)
    {
        // Create the object
        parent::__construct($HTMLDocument);

        // Set the HTML Factory
        $HTMLDocument->setHTMLFactory($FormFactory);
    }

    /**
     * Builds the frame along with the form action.
     *
     * @param mixed  $title      The dialog's title.
     * @param string $action     The form action to post the dialog to.
     *                           Leave empty in order to engage with module or application protocol.
     * @param bool   $background Defines whether the dialog popup will have a background.
     * @param string $type       The dialog buttons type.
     *                           Use class constants to define an OK/Cancel or Yes/No type.
     * @param bool   $fileUpload Enable the dialog form for file upload.
     *
     * @return $this
     */
    public function build($title = 'Dialog Frame', $action = '', $background = true, $type = self::TYPE_OK_CANCEL, $fileUpload = false)
    {
        // Set popup properties
        $this->background($background);

        // Build window frame
        parent::build($id = '', $class = 'dialogFrame', $title);

        // Build Form
        $this->form = new SimpleForm($this->getHTMLDocument(), $this->getFormFactory());
        $this->form->build($id = '', $action, $async = true, $fileUpload, $defaultButtons = false, $requiredNotes = true);
        $this->appendToBody($this->form);

        // Build Controls
        $this->buildControls($type);

        return $this;
    }

    /**
     * @return SimpleForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get the frame's form factory object.
     *
     * @return HTMLFormFactoryInterface|HTMLFactoryInterface
     */
    public function getFormFactory()
    {
        return $this->getHTMLDocument()->getHTMLFactory();
    }

    /**
     * Get the dialog's form id.
     *
     * @return string The form id.
     */
    public function getFormId()
    {
        return $this->form->attr('id');
    }

    /**
     * Builds the dialog controls.
     *
     * @param string $type The dialog buttons type.
     *                     Use class constants to define an OK/Cancel or Yes/No type.
     *                     Default type is OK/Cancel.
     *
     * @return $this
     */
    private function buildControls($type = self::TYPE_OK_CANCEL)
    {
        // Create dialog controls container
        $controlsContainer = $this->getFormFactory()->buildElement('div', '', '', 'dialogControls');
        $this->form->append($controlsContainer);

        // Button Container
        $btnContainer = $this->getFormFactory()->buildElement('div', '', '', 'ctrls');
        $controlsContainer->append($btnContainer);

        // Set button literals
        $lbl_submit = ($type == self::TYPE_OK_CANCEL ? 'ok' : 'yes');
        $lbl_reset = ($type == self::TYPE_OK_CANCEL ? 'cancel' : 'no');

        // Insert Controls
        $submitBtn = $this->getFormFactory()->buildSubmitButton($lbl_submit, '', '', 'dlgExec positive');
        $btnContainer->append($submitBtn);
        $resetBtn = $this->getFormFactory()->buildResetButton($lbl_reset, '', 'dlgCancel');
        $btnContainer->append($resetBtn);

        return $this;
    }
}
