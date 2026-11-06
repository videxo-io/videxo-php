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
 * @phpstan-type AudioOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
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
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
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
 *     preset?: 'podcast-audio'
 * }
 * @phpstan-type CompileRequest OperationsJobRequest|array{
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *     preset?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int,
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
 *         text?: list<TextLayer>
 *     }>,
 *     type?: 'video'|'audio',
 *     operation: 'concat'
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
 *         text?: list<TextLayer>
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
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
 *         text?: list<TextLayer>
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *     resize: array{
 *         width: int,
 *         height: int,
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     }|array{
 *         width: int,
 *         aspect: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
 *         pad_color?: 'black'|'white'|string,
 *         enlarge?: bool
 *     }|array{
 *         height: int,
 *         aspect: '16:9'|'9:16'|'1:1'|'4:5'|'4:3'|'3:4',
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
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
 *         text?: list<TextLayer>
 *     }>,
 *     extra_inputs?: array<array-key, string|array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         fit?: 'contain'|'cover'|'pad',
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     preset: 'web-720p'|'web-1080p'|'social-vertical'|'square',
 *     type?: 'video'
 * }
 * @phpstan-type CreateJobRequest array{
 *     input_files: array<array-key, string>,
 *     output_files: array<array-key, string>,
 *     command: string,
 *     webhook_url?: string|null,
 *     metadata?: array<array-key, string>
 * }
 * @phpstan-type GifOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     preset?: 'gif-preview'
 * }
 * @phpstan-type ImageOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     preset?: 'poster',
 *     type: 'image'
 * }
 * @phpstan-type JobRequest CreateJobRequest|OperationsJobRequest
 * @phpstan-type JobSpec array{
 *     inputs: array<array-key, array{
 *         url: string
 *     }|array{
 *         upload_id: string
 *     }>,
 *     outputs: list<Output>
 * }
 * @phpstan-type MetadataOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'json',
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
 * @phpstan-type Output VideoOutput|AudioOutput|ImageOutput|StoryboardOutput|GifOutput|MetadataOutput|PresetVideoOutput|PresetAudioOutput|PresetImageOutput|PresetGifOutput
 * @phpstan-type PresetAudioOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>
 *     }>,
 *     trim?: array{
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         duration?: int|float|string
 *     },
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
 *     preset: 'podcast-audio'
 * }
 * @phpstan-type PresetGifOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     preset: 'gif-preview'
 * }
 * @phpstan-type PresetImageOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     preset: 'poster'
 * }
 * @phpstan-type PresetOptions array{
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     at?: int|float|string|'auto',
 *     times?: list<int|float|string>,
 *     quality?: 'low'|'medium'|'high',
 *     interval?: int|float,
 *     columns?: int,
 *     rows?: int,
 *     tile_width?: int,
 *     exact_times?: bool,
 *     colors?: int
 * }
 * @phpstan-type PresetVideoOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     preset: 'web-720p'|'web-1080p'|'social-vertical'|'square'
 * }
 * @phpstan-type StoryboardOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *     exact_times?: bool
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
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
 *     preset?: 'web-720p'|'web-1080p'|'social-vertical'|'square'
 * }
 * @phpstan-type VideoOutput array{
 *     name?: string,
 *     filename?: string,
 *     format?: 'mp4'|'mov'|'webm'|'mkv'|'ts'|'mp3'|'m4a'|'aac'|'wav'|'flac'|'ogg'|'opus'|'jpg'|'png'|'gif'|'json',
 *     strip_metadata?: bool,
 *     input?: string,
 *     concat?: list<string|array{
 *         input: string,
 *         start?: int|float|string,
 *         end?: int|float|string,
 *         text?: list<TextLayer>
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
 *         anchor?: 'center'|'top'|'bottom'|'left'|'right',
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
 *         fps?: int
 *     },
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
