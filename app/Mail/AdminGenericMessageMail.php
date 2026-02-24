<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminGenericMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public ?string $preheader;
    public ?string $brandName;
    public ?string $brandColor;
    public ?string $footerNote;

    public ?string $greeting;
    public string $bodyText;
    public ?string $actionText;
    public ?string $actionUrl;
    public ?string $signature;

    /**
     * @param  array{
     *   subject:string,
     *   preheader?:string|null,
     *   brandName?:string|null,
     *   brandColor?:string|null,
     *   footerNote?:string|null,
     *   greeting?:string|null,
     *   body:string,
     *   actionText?:string|null,
     *   actionUrl?:string|null,
     *   signature?:string|null
     * }  $data
     */
    public function __construct(array $data)
    {
        $this->subjectLine = $data['subject'];
        $this->preheader   = $data['preheader']   ?? null;
        $this->brandName   = $data['brandName']   ?? null;
        $this->brandColor  = $data['brandColor']  ?? null;
        $this->footerNote  = $data['footerNote']  ?? null;

        $this->greeting    = $data['greeting']    ?? null;
        $this->bodyText    = $data['body'];
        $this->actionText  = $data['actionText']  ?? null;
        $this->actionUrl   = $data['actionUrl']   ?? null;
        $this->signature   = $data['signature']   ?? null;
    }

    public function build(): self
    {
        return $this->subject($this->subjectLine)
            ->view('admin.emails.message')
            ->with([
                'subject'    => $this->subjectLine,
                'preheader'  => $this->preheader,
                'brandName'  => $this->brandName,
                'brandColor' => $this->brandColor,
                'footerNote' => $this->footerNote,

                'greeting'   => $this->greeting,
                'body'       => $this->bodyText,
                'actionText' => $this->actionText,
                'actionUrl'  => $this->actionUrl,
                'signature'  => $this->signature,
            ]);
    }
}