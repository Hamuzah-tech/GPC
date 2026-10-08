<div class="admin-actions admin-actions-wrap">
    @if($showView ?? false)
        <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.show', $registration) }}">View</a>
    @endif

    @if($registration->isPending())
        <form method="POST" action="{{ route('admin.event-registrations.accept', $registration) }}" class="inline-action">
            @csrf
            <button type="button" class="btn-inline btn-inline-accept" data-confirm="Are you sure you want to accept this participant?" data-confirm-body="An acceptance email and digital event card will be generated automatically." data-confirm-ok="Accept" data-confirm-tone="positive">Accept</button>
        </form>
        <form method="POST" action="{{ route('admin.event-registrations.reject', $registration) }}" class="inline-action">
            @csrf
            <button type="button" class="btn-inline btn-inline-reject" data-confirm="Are you sure you want to reject this registration?" data-confirm-body="No registration ID, event card, or acceptance email will be created." data-confirm-ok="Reject">Reject</button>
        </form>
    @endif

    @if($registration->isAccepted())
        <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.card', $registration) }}">View Card</a>
        <a class="btn-inline btn-inline-neutral" href="{{ route('admin.event-registrations.card.download', $registration) }}">Download Card</a>
        <form method="POST" action="{{ route('admin.event-registrations.resend', $registration) }}" class="inline-action">
            @csrf
            <button type="button" class="btn-inline btn-inline-neutral" data-confirm="Resend the acceptance email?" data-confirm-body="The existing registration ID and event card will be used." data-confirm-ok="Send" data-confirm-tone="positive">Resend Email</button>
        </form>
        <form method="POST" action="{{ route('admin.event-registrations.reject', $registration) }}" class="inline-action">
            @csrf
            <button type="button" class="btn-inline btn-inline-reject" data-confirm="Are you sure you want to reject this participant?" data-confirm-body="Their digital event card will no longer be valid." data-confirm-ok="Reject">Reject</button>
        </form>
    @endif
</div>
