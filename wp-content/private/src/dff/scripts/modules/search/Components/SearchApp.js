// eslint-disable-next-line
import { h, Fragment, Component, createRef } from 'preact';
import axios from 'axios';
import { debounce } from 'lodash-es';

import { arrowRight } from './icons';

const { i18n, searchParams } = window;
const searchUrl = `${searchParams.homeUrl}?s=`;

class SearchApp extends Component {
  state = {
    results: [],
    loading: false,
    error: false,
    isHidden: true,
  };

  constructor(args) {
    super(args);
    this.inputRef = createRef();
    this.doSearch = debounce(this.handleSearch, 10);
    document.addEventListener('class-change', () => {
      this.setState({
        isHidden: !document.documentElement.classList.contains('search-active'),
      });
    });
  }

  getInputValue = () => this?.inputRef?.current?.value;

  handleSearch = async () => {
    const searchString = this.getInputValue();
    const { CancelToken } = axios;

    if (this.cancelToken) {
      this.cancelToken('User continued typing to search');
    }

    if (!searchString) {
      this.setState({
        loading: false,
        error: false,
      });
      return;
    }

    try {
      this.setState({
        loading: true,
        error: false,
      });
      const resp = await axios.get(
        `${searchParams.restUrl}wp/v2/search?context=embed&per_page=5&search=${searchString}`,
        {
          cancelToken: new CancelToken(c => {
            this.cancelToken = c;
          }),
        },
      );

      this.setState({
        loading: false,
        error: false,
        results: resp.data,
      });
    } catch (err) {
      if (axios.isCancel(err)) {
        return;
      }
      this.setState({
        loading: false,
        error: true,
      });
      console.error(err); // eslint-disable-line
    }
  };

  handleInputChange = () => {
    this.doSearch();
  };

  closeOnEsc = event => {
    if (event.keyCode === 27) {
      document.documentElement.classList.remove('search-active');
      // event.target.blur();
      const { handleClose } = this.props;
      handleClose();
      this.setState({
        isHidden: true,
      });
    }

    if (event.keyCode === 13) {
      window.location = searchUrl + this.getInputValue();
    }
  };

  render() {
    const { loading, error, results, isHidden } = this.state;
    const inputText = this.getInputValue();

    return (
      <>
        <label for="search-input" className="u-hiddenVisually">
          {i18n.search}
        </label>
        <input
          id="search-input"
          ref={this.inputRef}
          data-search-input
          type="search"
          placeholder={i18n.search}
          onInput={this.handleInputChange}
          onKeyUp={this.closeOnEsc}
          tabindex={isHidden ? '-1' : null}
          aria-live="polite"
        />
        {inputText && !isHidden && (
          <div className="page-searchResults">
            {loading && <p>{i18n.searching}</p>}
            {error && (
              <p>
                Something went wrong whilst trying to search...{' '}
                <button className="button button--ghost">Try Again?</button>
              </p>
            )}
            {!loading && !error && results.length === 0 && (
              <p>
                {i18n.noResultsFound} '{inputText}'
              </p>
            )}
            {!loading && !error && results.length > 0 && (
              <>
                <h3>{i18n.topSearch}</h3>
                <ul>
                  {results.map(result => (
                    <li key={result.id}>
                      <a href={result.url}>
                        {result.title} {arrowRight}
                      </a>
                    </li>
                  ))}
                </ul>
                <a href={`${searchUrl}${inputText}`}>{i18n.allSearch}</a>
              </>
            )}
          </div>
        )}
      </>
    );
  }
}

export default SearchApp;
