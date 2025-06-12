<div class="card mb-4">
    <div class="card-body">
        <div class="stepper-wrapper">
            @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX'] as $step)
                <a href="{{ route('evaluations.show', ['evaluation' => $evaluation->id, 'step' => $step]) }}" 
                   class="stepper-item {{ $currentStep === $step ? 'active' : '' }}">
                    <div class="stepper-icon">
                        {{ $step }}
                        @if($editableSections[$step] ?? false)
                            <span class="stepper-edit-indicator">🔴</span>
                        @endif
                    </div>
                    <div class="stepper-label">Bahagian {{ $step }}</div>
                </a>
            @endforeach
        </div>
    </div>
</div>