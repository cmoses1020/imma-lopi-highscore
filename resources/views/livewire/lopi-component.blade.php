<div class="relative flex justify-center items-center text-center h-screen max-h-screen overflow-hidden" x-data="lopi()" wire:ignore x-ref="main">
    <div>
        <img class="mx-auto w-[200px]" src="{{ Vite::asset('resources/lopi_assets/lopibig.png') }}"></img>
        <h1 class="mt-[10px] mb-[5px] text-4xl font-bold">Enjoy some Lopi! <span class="text-[#561378] text-sm">(actually Jim)</span></h1>
        
        <div x-text="count" class="text-[#444] text-[24px] font-bold mt-[10px] font-['Trebuchet_MS']"></div>
    
        <button
            @keydown.enter.prevent="null"
            @keydown.space.prevent="null"
            x-on:click="lopiPopup"
            class="block w-full text-[15px] bg-[#ffadd2] text-white font-bold py-[10px] px-[20px] border border-white hover:bg-opacity-80 hover:shadow-[#ffadd2] active:shadow-[#ffadd2] hover:shadow-md active:shadow-2xl active:bg-white active:text-[#ffadd2] active:border-[#ffadd2] shadow-md"
        >
            I'm Lopi
        </button>
        
        @auth
            <div class="m-[20px] text-[16px] text-black font-bold group">
                Welcome back, <span class="text-lopi-purple-900">{{ Auth::user()->name }}</span>!
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="font-semibold text-lopi-purple-900 hover:text-lopi-purple-400 text-xs"
                    >Logout</button>
                </form>
            </div>
            <div class="m-[20px] text-[16px] text-black font-bold group" x-show="rank">
                You are in <span x-text="rank" class="text-lopi-purple-600"></span> place
            </div>
        @endauth
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://www.youtube.com/@Punkalopi" target="_blank">
                Punkalopi's Youtube Channel
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(check it out, it's cool!)</span>
            </a>
        </div>
        <div class="m-[20px] text-[16px] text-black font-bold group">
            <a href="https://twitter.com/Punkalopi" target="_blank">
                Punkalopi's Twitter
                <span class="text-[#561378] text-sm group-hover:text-purple-500/80">(silly bird app, very fun!)</span>
            </a>
        </div>
        <div class="mt-[20px] text-lg font-['Impact']">
            <p>TOTAL CLICKER COUNT</p>
            <p class="text-4xl" x-text="totalClicks"></p>
        </div>
        <div class="mt-[20px] text-[15px] text-black font-bold group">
            <a href="{{ route('leaderboard') }}" class="text-[#561378]  group-hover:text-purple-500/80">
                Leader Boarder
            </a>
        </div>
        @guest
            <div class="text-[16px] text-black font-bold">
                <a href="{{ route('login') }}" class="text-[#561378] hover:text-purple-500/80">Login</a>
                or
                <a href="{{ route('register') }}" class="text-[#561378] hover:text-purple-500/80">Register<a/>
                to track your Lopi count!
            </div>
        @endguest
        <livewire:high-score-board />
    </div>

</div>

@pushOnce('scripts')
    <script>
        let lopi = () => {
            return {
                init() {
                    if (!this.isGuest) {
                        window.addEventListener('beforeunload', (event) => {
                            this.$wire.call('lopiCount', this.count)
                        })

                    }
                    this.$wire.call('getRankAndTotalClicks').then((data) => {
                        this.rank = data.rank
                        this.totalClicks = data.total_clicks
                        setInterval(() => {
                            this.$wire.call('getRankAndTotalClicks').then((data) => {
                                console.log(data)
                                this.rank = data.rank
                                this.totalClicks = data.total_clicks
                            })
                        }, 500)
                    })
                },
                sounds: [
                    new Audio("{{ Vite::asset('resources/lopi_assets/1.mp3') }}"),
                    new Audio("{{ Vite::asset('resources/lopi_assets/2.mp3') }}"),
                    new Audio("{{ Vite::asset('resources/lopi_assets/3.mp3') }}"),
                ],
                rank: null,
                isGuest: @js(auth()->guest()),
                lastUpdate: null,
                lopiImage: "{{ Vite::asset('resources/lopi_assets/lopi.png') }}",
                count: @js($lopiCount),
                totalClicks: 0,
                lopiPopup() {
                    index = Math.floor(Math.random()*(this.sounds.length))
                    this.sounds[index].cloneNode(true).play()
                    this.count++

                    let lopiImage = document.createElement('img')
                    lopiImage.src = this.lopiImage
                    lopiImage.style.position = 'absolute'
                    lopiImage.style.top = Math.floor(Math.random() * (window.innerHeight - 200)) + 'px'
                    lopiImage.style.left = Math.floor(Math.random() * (window.innerWidth - 200)) + 'px'
                    lopiImage.style.width = '250px'
                    lopiImage.style.height = 'auto'
                    lopiImage.style.zIndex = '9999'
                    lopiImage.style.transition = 'all 0.5s ease-in-out'
                    lopiImage.style.opacity = '0'
                    lopiImage.style.transform = 'scale(0.5)'
                    lopiImage.style.pointerEvents = 'none'
                    this.$refs.main.appendChild(lopiImage)
                    setTimeout(() => {
                        lopiImage.style.opacity = '1'
                        lopiImage.style.transform = 'scale(1)'
                    }, 100)
                    setTimeout(() => {
                        lopiImage.style.opacity = '0'
                        lopiImage.style.transform = 'scale(0.5)'
                    }, 750)
                    setTimeout(() => {
                        lopiImage.remove()
                    }, 1000)
                    {{-- debounce lopicount when being clicked allot --}}
                    this.$wire.call('click')
                    if (!this.isGuest && (this.lastUpdate === null || (new Date() - this.lastUpdate) > 2000)) {
                        this.lastUpdate = new Date()
                        this.$wire.call('lopiCount', this.count)
                    }
                }
            }
        }
    </script>
@endPushOnce