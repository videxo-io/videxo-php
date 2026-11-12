<?php

// Generated from the Videxo API's OpenAPI document. Do not edit: change the API and generate again.

declare(strict_types=1);

namespace Videxo;

/**
 * The request bodies of the API as PHPStan types, from its OpenAPI document. A method that takes a
 * body names its type, so PHPStan (or Psalm) checks the arrays you pass; at runtime they are plain
 * arrays, sent as JSON, and the API checks them again. Import one with
 * `@phpstan-import-type TranscodeRequest from \Videxo\Types`.
 *
 * @phpstan-type AdaptiveOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'zip',
 *     input?: string,
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     type: 'adaptive'
 * }
 * @phpstan-type AiUpscaleJobRequest array{
 *     inputs: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     outputs: list<AiUpscaleOutput>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>
 * }
 * @phpstan-type AiUpscaleJobSpec array{
 *     inputs: array<array-key, array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     outputs: list<array{
 *         name: string,
 *         type: 'ai_upscale',
 *         input: string,
 *         filename: string,
 *         resolution: '1080p'|'1440p'|'4k',
 *         frame_rate: 'source'|'smooth',
 *         trim: array{
 *             start: int|float,
 *             duration: int|float|null
 *         },
 *         audio: 'keep'|'remove'
 *     }>
 * }
 * @phpstan-type AiUpscaleOutput array{
 *     name?: string,
 *     input?: string,
 *     type: 'ai_upscale',
 *     resolution: '1080p'|'1440p'|'4k',
 *     frame_rate?: 'source'|'smooth',
 *     trim?: array{
 *         start?: int|float,
 *         duration?: int|float
 *     },
 *     audio?: 'keep'|'remove',
 *     filename?: string
 * }
 * @phpstan-type AudioOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         image: string,
 *         duration: int|float,
 *         motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         color: 'black'|'white'|string,
 *         duration: int|float,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     speed?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'podcast-audio',
 *     type: 'audio'
 * }
 * @phpstan-type AudioRequest array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     speed?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'podcast-audio'
 * }
 * @phpstan-type CompileRequest OperationsJobRequest|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'transcode'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'thumbnails'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'storyboard'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'gif'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'audio'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     inputs: list<string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }|array{
 *         source: string|array{
 *             url: string
 *         }|array{
 *             upload_id: string
 *         },
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     type?: 'video'|'audio',
 *     operation: 'concat'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     images: list<string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }|array{
 *         source: string|array{
 *             url: string
 *         }|array{
 *             upload_id: string
 *         },
 *         duration?: int|float,
 *         motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     duration?: int|float,
 *     motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *     transition?: array{
 *         type: 'fade'|'fade-black'|'fade-white'|'dissolve'|'wipe-left'|'wipe-right'|'wipe-up'|'wipe-down'|'slide-left'|'slide-right'|'slide-up'|'slide-down'|'circle-open'|'circle-close'|'radial'|'zoom-in'|'pixelize',
 *         duration?: int|float
 *     },
 *     operation: 'slideshow'
 * }|array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     filename?: string,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     operation: 'probe'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'scenes'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'transcribe'
 * }|array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl',
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     operation: 'adaptive'
 * }|array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     filename?: string,
 *     thresholdDb?: int|float,
 *     minSilenceSeconds?: int|float,
 *     speechPaddingSeconds?: int|float,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     operation: 'detect_silence'
 * }|array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     filename?: string,
 *     thresholdDb?: int|float,
 *     minSilenceSeconds?: int|float,
 *     speechPaddingSeconds?: int|float,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     type?: 'video'|'audio',
 *     operation: 'remove_silence'
 * }
 * @phpstan-type ConcatAudioRequest array{
 *     inputs: list<string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }|array{
 *         source: string|array{
 *             url: string
 *         }|array{
 *             upload_id: string
 *         },
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     speed?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'podcast-audio',
 *     type: 'audio'
 * }
 * @phpstan-type ConcatRequest ConcatVideoRequest|ConcatAudioRequest
 * @phpstan-type ConcatVideoRequest array{
 *     inputs: list<string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }|array{
 *         source: string|array{
 *             url: string
 *         }|array{
 *             upload_id: string
 *         },
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize: array{
 *         width: int,
 *         height: int,
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     }|array{
 *         width: int,
 *         aspect: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     }|array{
 *         height: int,
 *         aspect: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'web-720p'|'web-1080p'|'social-vertical'|'square',
 *     type?: 'video'
 * }|array{
 *     inputs: list<string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }|array{
 *         source: string|array{
 *             url: string
 *         }|array{
 *             upload_id: string
 *         },
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset: 'web-720p'|'web-1080p'|'social-vertical'|'square',
 *     type?: 'video'
 * }
 * @phpstan-type CreateJobRequest array{
 *     input_files: array<array-key, string>,
 *     output_files: array<array-key, string>,
 *     command: string,
 *     speed?: 'fast'|'standard'|'slow',
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>
 * }
 * @phpstan-type GifOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     colors?: int,
 *     strip_metadata?: bool,
 *     preset?: 'gif-preview',
 *     type: 'gif'
 * }
 * @phpstan-type GifRequest array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     colors?: int,
 *     strip_metadata?: bool,
 *     preset?: 'gif-preview'
 * }
 * @phpstan-type ImageOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     input?: string,
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'poster',
 *     type: 'image'
 * }
 * @phpstan-type JobNotice array{
 *     code: 'option_replaced',
 *     option: string,
 *     value: string,
 *     tier: 'fast'|'standard'|'slow'
 * }|array{
 *     code: 'speed_not_applied',
 *     output: string,
 *     encoder: string,
 *     requested: 'fast'|'standard'|'slow',
 *     tier: 'fast'|'standard'|'slow'
 * }
 * @phpstan-type JobRequest CreateJobRequest|OperationsJobRequest|AiUpscaleJobRequest
 * @phpstan-type JobResponseSpec JobSpec|AiUpscaleJobSpec
 * @phpstan-type JobSpec array{
 *     inputs: array<array-key, array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     outputs: list<Output>
 * }
 * @phpstan-type JobStep array{
 *     tool: 'ffmpeg'|'ffprobe'|'whisper'|'vips'|'oxipng'|'adaptive'|'scene_artifacts'|'silence',
 *     inputs: list<string>,
 *     outputs: list<string>,
 *     command: string|null
 * }
 * @phpstan-type MetadataOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'json',
 *     silence?: array{
 *         mode: 'detect',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     input?: string,
 *     type: 'metadata'
 * }
 * @phpstan-type OperationsJobRequest array{
 *     inputs: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     outputs: list<Output>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>
 * }
 * @phpstan-type Output VideoOutput|AudioOutput|ImageOutput|StoryboardOutput|GifOutput|MetadataOutput|ScenesOutput|TranscriptOutput|AdaptiveOutput|PresetVideoOutput|PresetAudioOutput|PresetImageOutput|PresetGifOutput
 * @phpstan-type PresetAudioOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         image: string,
 *         duration: int|float,
 *         motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         color: 'black'|'white'|string,
 *         duration: int|float,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     speed?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     strip_metadata?: bool,
 *     preset: 'podcast-audio'
 * }
 * @phpstan-type PresetGifOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     colors?: int,
 *     strip_metadata?: bool,
 *     preset: 'gif-preview'
 * }
 * @phpstan-type PresetImageOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     input?: string,
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset: 'poster'
 * }
 * @phpstan-type PresetOptions array{
 *     manifests?: 'hls'|'dash'|'both',
 *     max_resolution?: '720p'|'1080p',
 *     silence?: array{
 *         mode: 'detect'|'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     speed?: int|float,
 *     fps?: int|float,
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
 *     artifacts?: true,
 *     threshold?: int|float,
 *     language?: 'en'|'pl'
 * }
 * @phpstan-type PresetVideoOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         image: string,
 *         duration: int|float,
 *         motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         color: 'black'|'white'|string,
 *         duration: int|float,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset: 'web-720p'|'web-1080p'|'social-vertical'|'square'
 * }
 * @phpstan-type ScenesOutput array{
 *     name?: string,
 *     filename?: string,
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     threshold?: int|float,
 *     type: 'scenes',
 *     format?: 'json'
 * }|array{
 *     name?: string,
 *     filename?: string,
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     threshold?: int|float,
 *     type: 'scenes',
 *     artifacts: true,
 *     format?: 'zip'
 * }
 * @phpstan-type StoryboardOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     quality?: 'low'|'medium'|'high',
 *     exact_times?: bool,
 *     strip_metadata?: bool,
 *     type: 'storyboard'
 * }
 * @phpstan-type StoryboardRequest array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     quality?: 'low'|'medium'|'high',
 *     exact_times?: bool,
 *     strip_metadata?: bool
 * }
 * @phpstan-type Subtitles array{
 *     input: string,
 *     font?: 'inter'|'inter-bold'|'roboto'|'roboto-bold'|'open-sans'|'open-sans-bold'|'montserrat'|'montserrat-bold'|'poppins'|'poppins-bold'|'oswald'|'oswald-bold'|'bebas-neue'|'playfair-display'|'playfair-display-bold'|'merriweather'|'merriweather-bold'|'noto-sans'|'noto-sans-bold'|'noto-serif'|'noto-serif-bold'|'jetbrains-mono'|'jetbrains-mono-bold',
 *     size?: int,
 *     color?: 'black'|'white'|string,
 *     outline?: array{
 *         width?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     position?: 'bottom'|'top',
 *     margin?: int
 * }
 * @phpstan-type TextLayer array{
 *     text: string,
 *     font?: 'inter'|'inter-bold'|'roboto'|'roboto-bold'|'open-sans'|'open-sans-bold'|'montserrat'|'montserrat-bold'|'poppins'|'poppins-bold'|'oswald'|'oswald-bold'|'bebas-neue'|'playfair-display'|'playfair-display-bold'|'merriweather'|'merriweather-bold'|'noto-sans'|'noto-sans-bold'|'noto-serif'|'noto-serif-bold'|'jetbrains-mono'|'jetbrains-mono-bold',
 *     size?: int,
 *     color?: 'black'|'white'|string,
 *     opacity?: int|float,
 *     line_height?: int|float,
 *     position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *     margin?: int,
 *     offset_x?: int,
 *     offset_y?: int,
 *     box?: array{
 *         color?: 'black'|'white'|string,
 *         opacity?: int|float,
 *         padding?: int
 *     },
 *     outline?: array{
 *         width?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     shadow?: array{
 *         x?: int,
 *         y?: int,
 *         color?: 'black'|'white'|string,
 *         opacity?: int|float
 *     },
 *     start?: int|float|string,
 *     end?: int|float|string,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float
 *     }
 * }
 * @phpstan-type ThumbnailsRequest array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     optimize?: bool,
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'poster'
 * }
 * @phpstan-type TranscodeRequest array{
 *     input: string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     },
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'web-720p'|'web-1080p'|'social-vertical'|'square'
 * }
 * @phpstan-type TranscriptOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'json',
 *     input?: string,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     language: 'en'|'pl',
 *     type: 'transcript'
 * }
 * @phpstan-type Transition array{
 *     type: 'fade'|'fade-black'|'fade-white'|'dissolve'|'wipe-left'|'wipe-right'|'wipe-up'|'wipe-down'|'slide-left'|'slide-right'|'slide-up'|'slide-down'|'circle-open'|'circle-close'|'radial'|'zoom-in'|'pixelize',
 *     duration?: int|float
 * }
 * @phpstan-type VideoOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'webp'|'avif'|'gif'|'json'|'zip',
 *     silence?: array{
 *         mode: 'remove',
 *         thresholdDb?: int|float,
 *         minSilenceSeconds?: int|float,
 *         speechPaddingSeconds?: int|float
 *     },
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         image: string,
 *         duration: int|float,
 *         motion?: 'zoom-in'|'zoom-out'|'pan-left'|'pan-right'|'pan-up'|'pan-down',
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }|array{
 *         color: 'black'|'white'|string,
 *         duration: int|float,
 *         text?: list<TextLayer>,
 *         transition?: Transition
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
 *     crop?: array{
 *         x: int,
 *         y: int,
 *         width: int,
 *         height: int
 *     },
 *     rotate?: 90|180|270,
 *     flip?: 'horizontal'|'vertical',
 *     resize?: array{
 *         width?: int,
 *         height?: int,
 *         aspect?: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right'|'attention',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     },
 *     color?: array{
 *         brightness?: int|float,
 *         contrast?: int|float,
 *         saturation?: int|float,
 *         gamma?: int|float,
 *         hue?: int|float
 *     },
 *     sharpen?: array{
 *         amount?: int|float,
 *         size?: 3|5|7
 *     },
 *     blur?: array{
 *         strength: int,
 *         regions?: list<array{
 *             x: int,
 *             y: int,
 *             width: int,
 *             height: int
 *         }>
 *     },
 *     overlays?: list<array{
 *         input: string,
 *         position?: 'top-left'|'top'|'top-right'|'left'|'center'|'right'|'bottom-left'|'bottom'|'bottom-right',
 *         margin?: int,
 *         width?: int,
 *         opacity?: int|float,
 *         start?: int|float|string,
 *         end?: int|float|string
 *     }>,
 *     text?: list<TextLayer>,
 *     subtitles?: Subtitles,
 *     speed?: int|float,
 *     fps?: int|float,
 *     fade?: array{
 *         in?: int|float,
 *         out?: int|float,
 *         color?: 'black'|'white'
 *     },
 *     video?: array{
 *         codec?: 'h264'|'h265'|'vp9'|'av1'|'copy',
 *         quality?: 'low'|'medium'|'high'|'lossless',
 *         crf?: int,
 *         speed?: 'fast'|'standard'|'slow',
 *         encoder_preset?: 'ultrafast'|'superfast'|'veryfast'|'faster'|'fast'|'medium'|'slow',
 *         max_bitrate?: string,
 *         pixel_format?: 'yuv420p'|'yuv422p'|'yuv444p'|'yuv420p10le',
 *         faststart?: bool
 *     },
 *     audio?: false|array{
 *         input?: string,
 *         codec?: 'aac'|'mp3'|'opus'|'vorbis'|'flac'|'alac'|'pcm'|'copy',
 *         quality?: 'low'|'medium'|'high',
 *         bitrate?: string,
 *         sample_rate?: int,
 *         channels?: 1|2,
 *         volume?: int|float,
 *         normalize?: true|array{
 *             loudness?: int|float,
 *             true_peak?: int|float,
 *             range?: int|float
 *         },
 *         mix?: list<array{
 *             input: string,
 *             volume?: int|float
 *         }>
 *     },
 *     visualize?: array{
 *         style: 'waveform'|'spectrum',
 *         width?: int,
 *         height?: int,
 *         fps?: int,
 *         color?: 'black'|'white'|string
 *     },
 *     strip_metadata?: bool,
 *     preset?: 'web-720p'|'web-1080p'|'social-vertical'|'square',
 *     type: 'video'
 * }
 */
final class Types
{
    private function __construct()
    {
    }
}
