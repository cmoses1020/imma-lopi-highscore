<div>
    <div class="text-3xl mb-2">
        High Scores
    </div>
    <div class="space-y-2" x-data="scoreBoard">
        {{-- @foreach ($users as $user)
            <div class="flex justify-between">
                <div>
                    {{ $user->rankWithOrdinal }}
                </div>
                <div>
                    {{ $user->name }}:
                </div>
                <div>
                    {{ $user->click_count }}
                </div>
            </div>
        @endforeach --}}
        <template x-for="(user, index) in users" x-bind:key="index">
            <div class="flex justify-between"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
            >
                <div x-text="user.rank_with_ordinal"></div>
                <div x-text="user.name"></div>
                <div x-text="user.click_count"></div>
            </div>
        </template>
    </div>
</div>


@pushOnce('scripts')
    <script>
        let scoreBoard = () => {
            return {
                init() {
                    //set interval to get users every 1 seconds
                    setInterval(() => {
                        console.log(this.$wire)
                        this.$wire.getUsers().then((data) => {
                            this.users = data
                            console.log(data)
                        })
                    }, 1000)
                },
                users: @json($this->getUsers()),
                rank: null,
                totalClicks: 0,
            }
        }
    </script>
@endPushOnce