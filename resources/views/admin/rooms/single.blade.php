@extends('layouts.admin')

@section('title', __('Chat Conversation'))
@section('pageName', __('Chat Management'))

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المحادثات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض المحادثة</li>
    @endsection

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
        <style>
            .chat-history {
                max-height: calc(100vh - 300px);
                overflow-y: auto;
                padding: 0 1.5rem;
            }
            .chat-message {
                padding: 0.75rem 0;
            }
            .chat-message-left {
                margin-right: 20%;
            }
            .chat-message-right {
                margin-left: 20%;
                text-align: right;
            }
            .chat-message-wrapper {
                padding: 0.75rem 1.25rem;
                border-radius: 0.475rem;
                position: relative;
            }
            .chat-message-left .chat-message-wrapper {
                background-color: #f5f8fa;
                border-top-left-radius: 0;
            }
            .chat-message-right .chat-message-wrapper {
                background-color: rgba(var(--kt-primary-rgb), 0.1);
                border-top-right-radius: 0;
            }
            .chat-message-text {
                word-wrap: break-word;
            }
            .chat-message-time {
                font-size: 0.75rem;
                opacity: 0.8;
            }
            .chat-history-header {
                padding: 1.25rem 1.5rem;
                background-color: #f9f9f9;
            }
            .chat-input-container {
                padding: 1.25rem 1.5rem;
                background-color: #f9f9f9;
                border-top: 1px solid #eee;
            }
            .user-status {
                font-size: 0.825rem;
            }
            .message-sender-name {
                font-size: 0.825rem;
                font-weight: 500;
                margin-bottom: 0.25rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-scroll to bottom of chat
                const chatHistory = document.querySelector('.chat-history');
                chatHistory.scrollTop = chatHistory.scrollHeight;

                // Handle sending new messages
                const messageForm = document.getElementById('message-form');
                if (messageForm) {
                    messageForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const messageInput = document.getElementById('message-input');
                        const message = messageInput.value.trim();
                        
                        if (message) {
                            // Here you would typically send the message via AJAX
                            // For now, we'll just simulate it
                            console.log('Message sent:', message);
                            messageInput.value = '';
                            
                            // Scroll to bottom after new message
                            setTimeout(() => {
                                chatHistory.scrollTop = chatHistory.scrollHeight;
                            }, 100);
                        }
                    });
                }
            });
        </script>
    @endpush

    <div class="card">
        <!-- Chat Header -->
        <div class="card-header border-0">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('Chat Conversation') }}</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('admin.chats.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-outline ki-arrow-left fs-2 me-1"></i>
                    {{ __('Back to Chats') }}
                </a>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="card-body p-0">
            <div class="app-chat overflow-hidden">
                <div class="row g-0">
                    <!-- Chat History -->
                    <div class="col app-chat-history">
                        <div class="chat-history-wrapper">
                            <!-- Chat Header -->
                            <div class="chat-history-header border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex overflow-hidden align-items-center">
                                        <div class="symbol symbol-45px symbol-circle me-4">
                                            <img src="{{ $row->trip?->user?->avatar_url ?? asset('assets/media/avatars/blank.png') }}" 
                                                 alt="{{ $row->trip?->user?->full_name ?? 'Unknown User' }}">
                                        </div>
                                        <div class="chat-contact-info flex-grow-1">
                                            <h6 class="m-0 fw-bold">
                                                {{ __('Trip #') }}{{ $row->trip?->id ?? 'N/A' }} - {{ $row->trip?->user?->full_name ?? 'Unknown User' }}
                                            </h6>
                                            <small class="user-status text-muted d-block">
                                                {{ __('Between') }}: 
                                                <span class="text-primary">{{ $row->trip?->user?->full_name ?? 'Unknown User' }}</span> 
                                                {{ __('and') }} 
                                                <span class="text-primary">{{ $row->trip?->driver?->full_name ?? 'Unknown Driver' }}</span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-light-primary fs-8">
                                            {{ $row->created_at->format('d M Y, h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Messages -->
                            <div class="chat-history-body">
                                <ul class="list-unstyled chat-history">
                                    @forelse ($row->chat as $item)
                                        <li class="chat-message {{ ($item->sender_id == $row->trip?->driver?->id) ? 'chat-message-right' : 'chat-message-left' }}">
                                            <div class="d-flex overflow-hidden">
                                                <div class="chat-message-wrapper flex-grow-1">
                                                    <p class="message-sender-name mb-1">
                                                        @if ($item->sender_id == $row->trip?->driver?->id)
                                                            <i class="ki-outline ki-truck fs-4 text-primary align-middle me-2"></i>
                                                            {{ __('Driver') }}: {{ $row->trip?->driver?->full_name ?? 'Unknown Driver' }}
                                                        @else
                                                            <i class="ki-outline ki-user fs-4 text-primary align-middle me-2"></i>
                                                            {{ __('Client') }}: {{ $row->trip?->user?->full_name ?? 'Unknown User' }}
                                                        @endif
                                                    </p>
                                                    <div class="chat-message-text">
                                                        <p class="mb-0">{{ $item->message }}</p>
                                                    </div>
                                                    <div class="chat-message-time mt-1">
                                                        <i class="ki-outline ki-check-circle fs-4 text-success me-1"></i>
                                                        <small>
                                                            @php
                                                                $createdAt = $item->created_at;
                                                                $now = now();
                                                                
                                                                if ($createdAt->diffInHours($now) < 24) {
                                                                    echo $createdAt->format('h:i A');
                                                                } elseif ($createdAt->diffInYears($now) < 1) {
                                                                    echo $createdAt->format('M j, h:i A');
                                                                } else {
                                                                    echo $createdAt->format('M j, Y h:i A');
                                                                }
                                                            @endphp
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="text-center py-10">
                                            <div class="symbol symbol-100px symbol-circle mb-5">
                                                <div class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-messages fs-2x text-primary"></i>
                                                </div>
                                            </div>
                                            <h4 class="text-gray-600">{{ __('No messages yet') }}</h4>
                                            <p class="text-muted">{{ __('Start the conversation by sending a message') }}</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>

                            <!-- Message Input -->
                            {{-- <div class="chat-input-container">
                                <form id="message-form" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="chat_id" value="{{ $row->id }}">
                                    <div class="form-floating flex-grow-1 me-3">
                                        <textarea class="form-control" id="message-input" 
                                                  placeholder="{{ __('Type your message here...') }}" 
                                                  rows="1" style="min-height: 45px;"></textarea>
                                        <label for="message-input">{{ __('Type your message...') }}</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-6">
                                        <i class="ki-outline ki-send fs-2 me-1"></i>
                                        {{ __('Send') }}
                                    </button>
                                </form>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection